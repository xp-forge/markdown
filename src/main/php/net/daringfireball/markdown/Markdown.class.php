<?php namespace net\daringfireball\markdown;

/**
 * @see  http://daringfireball.net/projects/markdown/basics
 * @see  https://github.com/markdown/markdown.github.com/wiki/Implementations
 */
class Markdown extends \lang\Object {
  protected $tokens= array();
  protected $span= '\\';

  /**
   * Initializes default tokenss
   */
  public function __construct() {
    $this->addToken('&', function($line, $target) {
      if (-1 === ($s= $line->next(';'))) return false;
      $target->add(new Entity($line->slice($s)));
      return true;
    });
    $this->addToken('`', function($line, $target) {
      if ($line->matches('`` ')) {
        $target->add(new Code($line->ending(array(' ``', '``'), 3)));
      } else if ($line->matches('``')) {
        $target->add(new Code($line->ending('``')));
      } else {
        $target->add(new Code($line->ending('`')));
      }
      return true;
    });
    $this->addToken('<', function($line, $target) {
      if (preg_match('#<(([a-z]+://)[^ >]+)>#', $line, $m, 0, $line->pos())) {
        $target->add(new Link($m[1]));
      } else if (preg_match('#<(([^ @]+)@[^ >]+)>#', $line, $m, 0, $line->pos())) {
        $target->add(new Email($m[1]));
      } else {
        return false;
      }
      $line->forward(strlen($m[0]));
      return true;
    });

    // *Word* => Emphasis, **Word** => Strong emphasis
    $emphasis= function($line, $target) {
      $c= $line->chr();
      if ($line->matches($c.$c)) {
        $target->add(new Bold($line->ending($c.$c)));
      } else {
        $target->add(new Italic($line->ending($c)));
      }
      return true;
    };
    $this->addToken('*', $emphasis);
    $this->addToken('_', $emphasis);

    // Links and images: [A link](http://example.com), [A link](http://example.com "Title"),
    // [Google][goog] reference-style link, [Google][] implicit name,and finally [Google] [1] 
    // numeric references (-> spaces allowed!). Images almost identical except for leading
    // exclamation mark, e.g. ![An image](http://example.com/image.jpg)
    $tokenizer= $this;
    $parseLink= function($line, $target, $newInstance) use($tokenizer) {
      $title= null;
      $text= $line->matching('[]');
      $w= false;
      if ($line->matches('(')) {
        sscanf($line->matching('()'), '%[^" ] "%[^")]"', $url, $title);
        $node= $tokenizer->tokenize(new Line($text), new ParseTree());
      } else if ($line->matches('[') || $w= $line->matches(' [')) {
        $line->forward((int)$w);
        $node= new Text($text);
        if ('' === ($ref= $line->ending(']'))) {
          $url= '@'.strtolower($text);
        } else {
          $url= '@'.strtolower($ref);
        }
      }
      $target->add($newInstance($url, $node, $title));
      return true;
    };
    $this->addToken('[', function($line, $target) use($parseLink) {
      return $parseLink($line, $target, function($url, $text, $title) {
        return new Link($url, $text, $title);
      });
    });
    $this->addToken('!', function($line, $target) use($parseLink) {
      if (!$line->matches('![')) return false;
      $line->forward(1);
      return $parseLink($line, $target, function($url, $text, $title) {
        return new Image($url, $text, $title);
      });
    });
  }

  /**
   * Adds a handler to parse starting with a given character
   *
   * The handler is a closure of the following form:
   * ```php
   * $handler= function($line, $target) {
   *   $target->add(new Code($line->ending('`')));
   *   return true;
   * };
   * ```
   * The tokens handler needs to return whether it handled the token.
   * 
   * @param string $char A single character starting the token
   * @param var $tokens The closure
   */
  public function addToken($char, $tokens) {
    $this->tokens[$char]= $tokens;
    $this->span.= $char;
  }

  /**
   * Tokenize a line
   *
   * @param  net.daringfireball.markdown.Line $l The line
   * @param  net.daringfireball.markdown.Node $target The target node to add nodes to
   * @return net.daringfireball.markdown.Node The target
   */
  public function tokenize($line, Node $target) {
    $safe= 0;
    $l= $line->length();
    while ($line->pos() < $l) {
      $t= '';
      $c= $line->chr();
      if ('\\' === $c) {
        $t= $line{$line->pos() + 1};
        $line->forward(2);          // Skip escape, don't tokenize next character
      } else if (isset($this->tokens[$c])) {
        if (!$this->tokens[$c]($line, $target)) {
          $t= $c;                   // Push back
          $line->forward();
        }
      }

      $target->add(new Text($t.$line->until($this->span)));
      if ($safe++ > $l) throw new \lang\IllegalStateException('Endless loop detected');
    }
    return $target;
  }

  /**
   * Transform a given input and returns the output
   *
   * @param  string $in markdown
   * @param  [:net.daringfireball.markdown.Link] $urls
   * @return string markup
   */
  public function transform($in, $urls= array()) {
    $lines= new \text\StringTokenizer($in, "\n");
    $context= new ToplevelContext();
    $context->tokenizer= $this;
    $tree= $context->parse($lines);
    return $tree->emit($tree->urls + $urls);
  }
}