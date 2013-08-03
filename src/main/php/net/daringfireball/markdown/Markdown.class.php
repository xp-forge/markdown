<?php namespace net\daringfireball\markdown;

/**
 * @see  http://daringfireball.net/projects/markdown/basics
 * @see  https://github.com/markdown/markdown.github.com/wiki/Implementations
 */
class Markdown extends \lang\Object {
  protected $tokens= array();
  protected $handlers= array();

  /**
   * Initializes default tokens and handlers
   */
  public function __construct() {

    // Tokens
    $this->addToken('&', function($line, $target, $tokenizer) {
      if (-1 === ($s= $line->next(';'))) return false;
      $target->add(new Entity($line->slice($s)));
      return true;
    });
    $this->addToken('`', function($line, $target, $tokenizer) {
      if ($line->matches('`` ')) {
        $target->add(new Code($line->ending(array(' ``', '``'), 3)));
      } else if ($line->matches('``')) {
        $target->add(new Code($line->ending('``')));
      } else {
        $target->add(new Code($line->ending('`')));
      }
      return true;
    });
    $this->addToken('<', function($line, $target, $tokenizer) {
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
    $emphasis= function($line, $target, $tokenizer) {
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
    $parseLink= function($line, $target, $tokenizer, $newInstance) {
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
    $this->addToken('[', function($line, $target, $tokenizer) use($parseLink) {
      return $parseLink($line, $target, $tokenizer, function($url, $text, $title) {
        return new Link($url, $text, $title);
      });
    });
    $this->addToken('!', function($line, $target, $tokenizer) use($parseLink) {
      if (!$line->matches('![')) return false;
      $line->forward(1);
      return $parseLink($line, $target, $tokenizer, function($url, $text, $title) {
        return new Image($url, $text, $title);
      });
    });

    // Handlers
    $this->addHandler('', function() { });
  }

  /**
   * Adds a handler to parse starting with a given character
   *
   * The handler is a closure of the following form:
   * ```php
   * $handler= function($line, $target, $tokenizer) {
   *   $target->add(new Code($line->ending('`')));
   *   return true;
   * };
   * ```
   * The tokens handler needs to return whether it handled the token.
   * 
   * @param string $char A single character starting the token
   * @param var $handler The closure
   */
  public function addToken($char, $handler) {
    $this->tokens[$char]= $handler;
  }

  /**
   * Adds a handler to parse start of a line
   * 
   * @param string $char A single character starting the token
   * @param var $handler The closure
   */
  public function addHandler($pattern, $handler) {
    $this->handlers[$pattern]= $handler;
  }

  /**
   * Transform a given input and returns the output
   *
   * @param  var $in markdown either a string or a net.daringfireball.markdown.Input
   * @param  [:net.daringfireball.markdown.Link] $urls
   * @return string markup
   */
  public function transform($in, $urls= array()) {
    $context= new ToplevelContext();
    $context->setTokens($this->tokens);
    $context->setHandlers($this->handlers);
    $tree= $context->parse($in instanceof Input ?: new StringInput((string)$in));
    return $tree->emit($tree->urls + $urls);
  }
}