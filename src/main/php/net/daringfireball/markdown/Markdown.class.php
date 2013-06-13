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
        $s= $line->next(array(' ``', '``'));  // Be forgiving about incorrect closing
        $target->add(new Code($line->slice($s, +3)));
      } else if ($line->matches('``')) {
        $target->add(new Code($line->until('``')));
      } else {
        $target->add(new Code($line->until('`')));
      }
    });
    $this->addHandler(array('*', '_'), function($line, $target) {
      $c= $line->chr();
      if ($line->matches($c.$c)) {            // Strong: **Word**
        $target->add(new Bold($line->until($c.$c)));
      } else {                                // Emphasis: *Word*
        $target->add(new Italic($line->until($c)));
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
    $parseLink= function($line, $target, $newInstance) {
      $title= null;
      $text= $line->until(']');
      $w= false;
      if ($line->matches('(')) {
        sscanf($line->matching('()'), '%[^" ] "%[^")]"', $url, $title);
      } else if ($line->matches('[') || $w= $line->matches(' [')) {
        $line->forward((int)$w);
        if ('' === ($ref= $line->until(']'))) {
          $url= '@'.strtolower($text);
        } else {
          $url= '@'.strtolower($ref);
        }
      }
      $target->add($newInstance($url, $text, $title));
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
   *   $target->add(new Code($line->until('`')));
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
      '(?P<def>\s{0,3}\[([^\]]+)\]:\s+([^ ]+))'.
    ')/';
    $lines= new \text\StringTokenizer($in, "\n");

    $tokens= new ParseTree();
    $definitions= array();
    $target= $tokens->add(new Paragraph());
    $list= $quot= null;
    while ($lines->hasMoreTokens()) {
      $line= $lines->nextToken();

      // An empty line by itself ends the last element and starts a new paragraph.
      if ('' === $line) {
        $target= $tokens->add(new Paragraph());
        continue;
      }

      // Check what line begins with
      $m= preg_match($begin, $line, $tag);
      if ($m) {
        if (isset($tag['header']) && '' !== $tag['header']) {
          $target= $tokens->append(new Header(substr_count($tag['header'], '#')));
          $line= rtrim($line, ' #');
        } else if (isset($tag['ul']) && '' !== $tag['ul']) {
          $list || $list= $tokens->append(new Listing('ul'));
          $target= $list->add(new ListItem());
        } else if (isset($tag['ol']) && '' !== $tag['ol']) {
          $list || $list= $tokens->append(new Listing('ol'));
          $target= $list->add(new ListItem());
        } else if (isset($tag['blockquote']) && '' !== $tag['blockquote']) {
          $quot || $quot= $tokens->append(new BlockQuote());
          $target= $quot;
        } else if (isset($tag['hr']) && '' !== $tag['hr']) {
          $tokens->append(new Ruler());
          continue;
        } else if (isset($tag['underline']) && '' !== $tag['underline']) {
          $end= $target->size()- 1;
          $last= $target->get($end);
          $target->set($end, new Header('=' === $tag['underline']{0} ? 1 : 2))->add($last);
          continue;
        } else if (isset($tag['def']) && '' !== $tag['def']) {
          $title= trim(substr($line, strlen($tag[0])));
          if ('' !== $title && 0 === strcspn($title, '(\'"')) {
            $title= trim($title, $def[$title{0}]);
          } else {
            $title= null;
          }
          $definitions[strtolower($tag[11])]= new Link($tag[12], null, $title);
          continue;
        }
        $line= substr($line, strlen($tag[0]));
      }

      // Tokenize line
      $safe= 0;
      $l= new Line($line);
      while ($l->pos() < $l->length()) {
        $t= '';
        $c= $l->chr();
        if ('\\' === $c) {
          $t= $l{$l->pos() + 1};
          $l->forward(2);             // Skip escape, don't tokenize next character
        } else if (isset($this->handler[$c])) {
          if (false === $this->handler[$c]($l, $target)) {
            $t= $c;                   // Push back
            $l->forward();
          }
        }
        $p= strcspn($l, $this->span, $l->pos());
        $target->add(new Text($t.substr($l, $l->pos(), $p)));
        $l->forward($p);

        if ($safe++ > 100) throw new \lang\IllegalStateException('Endless loop detected');
      }
    }
    // \util\cmd\Console::writeLine('@-> ', $tokens, ' & ', $definitions);

    return $tokens->emit($definitions);
  }
}