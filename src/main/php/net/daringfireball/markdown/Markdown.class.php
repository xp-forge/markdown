<?php namespace net\daringfireball\markdown;

/**
 * @see  http://daringfireball.net/projects/markdown/basics
 * @see  https://github.com/markdown/markdown.github.com/wiki/Implementations
 */
class Markdown extends \lang\Object {
  protected $handler= array();
  protected $span= '\\';

  /**
   * Initializes default handlers
   */
  public function __construct() {
    $this->addHandler('&', function($line, $target) {
      if (-1 === ($s= $line->next(';'))) return false;
      $target->add(new Entity($line->slice($s)));
    });
    $this->addHandler('`', function($line, $target) {
      if ($line->matches('`` ')) {
        $target->add(new Code($line->ending(array(' ``', '``'), 3)));
      } else if ($line->matches('``')) {
        $target->add(new Code($line->ending('``')));
      } else {
        $target->add(new Code($line->ending('`')));
      }
    });
    $this->addHandler(array('*', '_'), function($line, $target) {
      $c= $line->chr();
      if ($line->matches($c.$c)) {            // Strong: **Word**
        $target->add(new Bold($line->ending($c.$c)));
      } else {                                // Emphasis: *Word*
        $target->add(new Italic($line->ending($c)));
      }
    });
    $this->addHandler('<', function($line, $target) {
      if (preg_match('#<(([a-z]+://)[^ >]+)>#', $line, $m, 0, $line->pos())) {
        $target->add(new Link($m[1]));
      } else if (preg_match('#<(([^ @]+)@[^ >]+)>#', $line, $m, 0, $line->pos())) {
        $target->add(new Email($m[1]));
      } else {
        return false;
      }
      $line->forward(strlen($m[0]));
    });

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
    };
    $this->addHandler('[', function($line, $target) use($parseLink) {
      return $parseLink($line, $target, function($url, $text, $title) {
        return new Link($url, $text, $title);
      });
    });
    $this->addHandler('!', function($line, $target) use($parseLink) {
      if (!$line->matches('![')) return -1;
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
   * };
   * ```
   * 
   * @param var $arg Either a single character or an array of alternative characters
   * @param var $handler The closure
   */
  public function addHandler($arg, $handler) {
    foreach ((array)$arg as $char) {
      $this->handler[$char]= $handler;
      $this->span.= $char;
    }
  }

  /**
   * Tokenize a line
   *
   * @param  net.daringfireball.markdown.Line $l The line
   * @param  net.daringfireball.markdown.Node $target The target node to add nodes to
   * @return net.daringfireball.markdown.Node The target
   */
  public function tokenize($line, $target) {
    $safe= 0;
    $l= $line->length();
    while ($line->pos() < $l) {
      $t= '';
      $c= $line->chr();
      if ('\\' === $c) {
        $t= $line{$line->pos() + 1};
        $line->forward(2);             // Skip escape, don't tokenize next character
      } else if (isset($this->handler[$c])) {
        if (false === $this->handler[$c]($line, $target)) {
          $t= $c;                   // Push back
          $line->forward();
        }
      }

      $target->add(new Text($t.$line->until($this->span)));
      if ($safe++ > 100) throw new \lang\IllegalStateException('Endless loop detected');
    }
    return $target;
  }

  /**
   * Transform a given input and returns the output
   *
   * @param  string $in markdown
   * @return string markup
   */
  public function transform($in) {
    static $def= array('(' => '()', '"' => '"', "'" => "'");

    // * Atx-style headers "#" -> h1, "##" -> h2, ... etc.
    // * Setext-style headers are "underlined"
    // * "*", "+" or "-" -> ul/li
    // * ">" or "> >" -> block quoting
    // * [0-9]"." -> ol/li
    // * [id]: http://example.com "Link"
    $begin= '/^('.
      '(?P<header>#{1,6} )|'.
      '(?P<underline>(={3,}|-{3,}))|'.
      '(?P<hr>(\* ?){3,}$)|'.
      '(?P<ul>[+\*\-] )|'.
      '(?P<ol>[0-9]+\. )|'.
      '(?P<blockquote>\> )|'.
      '(?P<code>    |\t)|'.
      '(?P<def>\s{0,3}\[([^\]]+)\]:\s+([^ ]+))'.
    ')/';
    $lines= new \text\StringTokenizer($in, "\n");

    $tokens= new ParseTree();
    $definitions= array();
    $target= $tokens->add(new Paragraph());
    $quot= $code= null;
    $list= array();
    $empty= false;
    while ($lines->hasMoreTokens()) {
      $line= $lines->nextToken();
      $offset= 0;

      // List context vs. top-level paragraph
      if ($list) {

        // An empty line makes the list use paragraphs.
        if ('' === $line) {
          $empty= true;
          continue;
        }

        // Indented elements form additional paragpraphs inside list items. If 
        // the line doesn't start with a list bullet, this means the list is at
        // its end.
        if (preg_match('/^(\s+)?([+*-]+|[0-9]+\.) /', $line, $m)) {
          $empty && $list[0]->paragraphs= true;
          $empty= false;

          // Check whether we need to indent / dedent the list level
          $level= strlen($m[1]) / 2;
          $current= sizeof($list) - 1;
          if ($level > $current) {
            array_unshift($list, $target->add(new Listing('ul')));
          } else if ($level < $current) {
            array_shift($list);
          }

          // Add list item
          $target= $list[0]->add(new ListItem())->add(new Paragraph());
          $offset= strlen($m[0]);
        } else if ('  ' === substr($line, 0, 2)) {
          $target= $list[0]->last()->add(new Paragraph());
          $offset= 2;
        } else {
          array_shift($list);
          $list || $target= null;
          $empty= false;
        }
      } else {

        // An empty line by itself ends the last element and starts a new
        // paragraph (if there are any more lines)
        if ('' === $line) {
          $target= null;
          continue;
        }

        // Check what line begins with
        $m= preg_match($begin, $line, $tag);
        if ($m) {
          if (isset($tag['header']) && '' !== $tag['header']) {
            $target= $tokens->append(new Header(substr_count($tag['header'], '#')));
            $line= rtrim($line, ' #');
          } else if (isset($tag['ul']) && '' !== $tag['ul']) {
            $list || array_unshift($list, $tokens->append(new Listing('ul')));
            $target= $list[0]->add(new ListItem())->add(new Paragraph());
          } else if (isset($tag['ol']) && '' !== $tag['ol']) {
            $list || array_unshift($list, $tokens->append(new Listing('ol')));
            $target= $list[0]->add(new ListItem())->add(new Paragraph());
          } else if (isset($tag['blockquote']) && '' !== $tag['blockquote']) {
            $quot || $quot= $tokens->append(new BlockQuote());
            $target= $quot;
          } else if (isset($tag['hr']) && '' !== $tag['hr']) {
            $tokens->append(new Ruler());
            continue;
          } else if (isset($tag['code']) && '' !== $tag['code']) {
            $code || $code= $tokens->append(new CodeBlock());
            $target= $code;
          } else if (isset($tag['underline']) && '' !== $tag['underline']) {
            $paragraph= $tokens->last();
            $text= $paragraph->remove($paragraph->size() - 1);
            $tokens->append(new Header('=' === $tag['underline']{0} ? 1 : 2))->add($text);
            $target= null;
            continue;
          } else if (isset($tag['def']) && '' !== $tag['def']) {
            $title= trim(substr($line, strlen($tag[0])));
            if ('' !== $title && 0 === strcspn($title, '(\'"')) {
              $title= trim($title, $def[$title{0}]);
            } else {
              $title= null;
            }
            $definitions[strtolower($tag[12])]= new Link($tag[13], null, $title);
            continue;
          }
          $offset= strlen($tag[0]);
        }
      }

      // We got here, so there is more text, and no target -> we need to open
      // a new paragraph.
      if (null === $target) {
        $target= $tokens->append(new Paragraph());
      }

      // If previous line was text, add a newline
      // * Hello\nWorld -> <p>Hello\nWorld</p>
      // * Hello\n\nWorld -> <p>Hello</p><p>World</p>
      $last= $target->last();
      if ($last instanceof Text) {
        $last->value.= "\n";
      }

      $this->tokenize(new Line($line, $offset), $target);
    }
    // \util\cmd\Console::writeLine('@-> ', $tokens, ' & ', $definitions);

    return $tokens->emit($definitions);
  }
}