<?php namespace net\daringfireball\markdown;

/**
 * @see  http://daringfireball.net/projects/markdown/basics
 */
class Markdown extends \lang\Object {
  protected $handler= array();
  protected $span= '\\';

  /**
   * Initializes default handlers
   */
  public function __construct() {
    $this->addHandler('&', function($line, $o, $target) {
      if (false === ($s= strpos($line, ';', $o + 1))) return -1;
      $target->add(new Entity(substr($line, $o, $s - $o + 1)));
      return $s + 1;
    });
    $this->addHandler('`', function($line, $o, $target) {
      $s= strpos($line, '`', $o + 1);
      $target->add(new Code(substr($line, $o + 1, $s - $o - 1)));
      return $s + 1;
    });
    $this->addHandler(array('*', '_'), function($line, $o, $target) {
      if ($line{$o} === $line{$o + 1}) {    // Strong: **Word**
        $s= strpos($line, $line{$o}.$line{$o + 1}, $o + 1);
        $target->add(new Bold(substr($line, $o + 2, $s - $o - 2)));
        return $s + 2;
      } else {                              // Emphasis: *Word*
        $s= strpos($line, $line{$o}, $o + 1);
        $target->add(new Italic(substr($line, $o + 1, $s - $o - 1)));
        return $s + 1;
      }
    });
    $this->addHandler('<', function($line, $o, $target) {
      if (preg_match('#(([a-z]+://)[^ >]+)>#', $line, $m, 0, $o + 1)) {
        $target->add(new Link($m[1]));
      } else if (preg_match('#(([^ @]+)@[^ >]+)>#', $line, $m, 0, $o + 1)) {
        $target->add(new Email($m[1]));
      } else {
        return -1;
      }
      return $o + strlen($m[1]) + 2;
    });

    // Links and images: [A link](http://example.com), [A link](http://example.com "Title"),
    // [Google][goog] reference-style link, [Google][] implicit name,and finally [Google] [1] 
    // numeric references (-> spaces allowed!). Images almost identical except for leading
    // exclamation mark, e.g. ![An image](http://example.com/image.jpg)
    $parseLink= function($line, $o, $target, $newInstance) {
      $title= null;
      $s= strpos($line, ']', $o + 1);
      $text= substr($line, $o + 1, $s - $o - 1);
      $o= $s + 1;
      $w= 0;
      if ('(' === $line{$o}) {
        $s= strpos($line, ')', $o + 1);
        sscanf(substr($line, $o + 1, $s - $o - 1), '%[^" )] "%[^")]"', $url, $title);
        $o= $s + 1;
      } else if ('[' === $line{$o} || $w= (' ' === $line{$o} && '[' === $line{$o + 1})) {
        $s= strpos($line, ']', $o + $w + 1);
        if ($s - $o - $w <= 1) {    // []
          $url= '@'.strtolower($text);
        } else {
          $url= '@'.strtolower(substr($line, $o + $w + 1, $s - $o - $w - 1));
        }
        $o= $s + 1;
      }
      $target->add($newInstance($url, $text, $title));
      return $o;
    };
    $this->addHandler('[', function($line, $o, $target) use($parseLink) {
      return $parseLink($line, $o, $target, function($url, $text, $title) {
        return new Link($url, $text, $title);
      });
    });
    $this->addHandler('!', function($line, $o, $target) use($parseLink) {
      if ('[' !== $line{$o + 1}) return -1;
      $o++;
      return $parseLink($line, $o, $target, function($url, $text, $title) {
        return new Image($url, $text, $title);
      });
    });
  }

  /**
   * Adds a handler to parse starting with a given character
   *
   * The handler is a closure of the following form:
   * ```php
   * $handler= function($line, $o, $target) {
   *   $s= strpos($line, $line{$o}, $o + 1);
   *   $target->add(new Code(substr($line, $o + 1, $s - $o - 1)));
   *   return $s + 1;
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
   * Transform a given input and returns the output
   *
   * @param  string $in markdown
   * @return string markup
   */
  public function transform($in) {
    static $def= array('(' => '()', '"' => '"', "'" => "'");

    $lines= new \text\StringTokenizer($in, "\n");

    $tokens= new ParseTree();
    $definitions= array();
    $target= $tokens;
    $list= null;
    while ($lines->hasMoreTokens()) {
      $line= $lines->nextToken();

      // TODO: Rulers

      // Check what line begins with:
      // * "#" -> h1, "##" -> h2, ... etc.
      // * "*", "+" or "-" -> ul/li
      // * [0-9]"." -> ol/li
      // * [id]: http://example.com "Link"
      $m= preg_match('/^((?P<header>#{1,6} )|(?P<ul>[+\*\-] )|(?P<ol>[0-9]+\. )|(?P<def>\s{0,3}\[([^\]]+)\]:\s+([^ ]+)))/', $line, $tag);
      if ($m) {
        if (isset($tag['header']) && '' !== $tag['header']) {
          $target= $target->add(new Header(substr_count($tag['header'], '#')));
        } else if (isset($tag['ul']) && '' !== $tag['ul']) {
          $list || $list= $target->add(new Listing('ul'));
          $target= $list->add(new ListItem());
        } else if (isset($tag['ol']) && '' !== $tag['ol']) {
          $list || $list= $target->add(new Listing('ol'));
          $target= $list->add(new ListItem());
        } else if (isset($tag['def']) && '' !== $tag['def']) {
          $title= trim(substr($line, strlen($tag[0])));
          if ('' !== $title && 0 === strcspn($title, '(\'"')) {
            $title= trim($title, $def[$title{0}]);
          } else {
            $title= null;
          }
          $definitions[strtolower($tag[6])]= new Link($tag[7], null, $title);
          continue;
        }
        $line= substr($line, strlen($tag[0]));
      } else {
        $target= $tokens;
      }

      // Tokenize line
      $o= 0;
      $l= strlen($line);
      $safe= 0;
      while ($o < $l) {
        $t= '';
        if ('\\' === $line{$o}) {
          $t= $line{$o + 1};
          $o+= 2;             // Skip escape, don't tokenize next character
        } else if (isset($this->handler[$line{$o}])) {
          $r= $this->handler[$line{$o}]($line, $o, $target);
          if (-1 === $r) {
            $t= $line{$o};    // Push back
            $o++;
          } else {
            $o= $r;           // Forward
          }
        }
        $p= strcspn($line, $this->span, $o);
        $target->add(new Text($t.substr($line, $o, $p)));
        $o+= $p;

        if ($safe++ > 10) throw new \lang\IllegalStateException('Endless loop detected');
      }
    }
    // \util\cmd\Console::writeLine('@-> ', $tokens, ' & ', $definitions);

    return $tokens->emit($definitions);
  }
}