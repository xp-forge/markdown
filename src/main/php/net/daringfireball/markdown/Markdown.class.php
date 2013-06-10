<?php namespace net\daringfireball\markdown;

/**
 * @see  http://daringfireball.net/projects/markdown/basics
 */
class Markdown extends \lang\Object {
  
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
      $m= preg_match('/^((?P<header>#{1,6} )|(?P<ul>[+\*\-] )|(?P<ol>[0-9]+\. )|(?P<def>\[([^\]]+)\]:\s+([^ ]+)))/', $line, $tag);
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
      // *italic*, _italic_, **bold**, __bold__, [link](http://link), &quot;
      $o= 0;
      $l= strlen($line);
      $safe= 0;
      while ($o < $l) {
        $t= '';
        if ('&' === $line{$o}) {    // Escape standalone ampersands, leave entities as-is
          if (false !== ($s= strpos($line, ';', $o + 1))) {
            $target->add(new Entity(substr($line, $o, $s - $o + 1)));
            $o= $s + 1;
          } else {
            $t= '&';
            $o++;
          }
        } else if (('*' === $line{$o} && '*' === $line{$o + 1}) || ('_' === $line{$o} && '_' === $line{$o + 1})) {
          $s= strpos($line, $line{$o}.$line{$o + 1}, $o + 1);
          $target->add(new Bold(substr($line, $o + 2, $s - $o - 2)));
          $o= $s + 2;
        } else if ('*' === $line{$o} || '_' == $line{$o}) {
          $s= strpos($line, $line{$o}, $o + 1);
          $target->add(new Italic(substr($line, $o + 1, $s - $o - 1)));
          $o= $s + 1;
        } else if ('`' == $line{$o}) {
          $s= strpos($line, $line{$o}, $o + 1);
          $target->add(new Code(substr($line, $o + 1, $s - $o - 1)));
          $o= $s + 1;
        } else if ('[' === $line{$o}) {
          $title= null;
          $s= strpos($line, ']', $o + 1);
          $text= substr($line, $o + 1, $s - $o - 1);
          $o= $s + 1;

          // [A link](http://example.com), [A link](http://example.com "Title"),
          // [Google][goog] reference-style link, [Google][] implicit name,
          // and finally [Google] [1] numeric references (-> spaces allowed!)
          $w= 0;
          if ('(' === $line{$o}) {
            $s= strpos($line, ')', $o + 1);
            sscanf(substr($line, $o + 1, $s - $o - 1), '%[^" )] "%[^")]"', $url, $title);
            $o= $s + 1;
          } else if ('[' === $line{$o} || $w= (' ' === $line{$o} && '[' === $line{$o + 1})) {
            $s= strpos($line, ']', $o + $w + 1);
            if ($s - $o - $w <= 1) {
              $url= '@'.strtolower($text);
            } else {
              $url= '@'.strtolower(substr($line, $o + $w + 1, $s - $o - $w - 1));
            }
            $o= $s + 1;
          }
          $target->add(new Link($url, $text, $title));
        }

        $p= strcspn($line, '*&[]`', $o);
        $target->add(new Text($t.substr($line, $o, $p)));
        $o+= $p;

        if ($safe++ > 10) throw new \lang\IllegalStateException('Endless loop detected');
      }
    }
    // \util\cmd\Console::writeLine('@-> ', $tokens, ' & ', $definitions);

    return $tokens->emit($definitions);
  }
}