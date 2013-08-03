<?php namespace net\daringfireball\markdown;

class ToplevelContext extends Context {

  public function parse($lines) {
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

    $result= new ParseTree();
    $result->add(new Paragraph());
    $target= null;
    while ($lines->hasMoreTokens()) {
      $line= new Line($lines->nextToken());

      // An empty line by itself ends the last element and starts a new
      // paragraph (if there are any more lines)
      if (0 === $line->length()) {
        $target= null;
      }

      // Check what line begins with
      $m= preg_match($begin, $line, $tag);
      if ($m) {
        if (isset($tag['header']) && '' !== $tag['header']) {
          $target= $result->append(new Header(substr_count($tag['header'], '#')));
          $line= new Line(rtrim($line, ' #'));
        } else if (isset($tag['ul']) && '' !== $tag['ul']) {
          $lines->pushBack($line."\n");
          $result->append($this->enter(new ListContext('ul'))->parse($lines));
          $target= null;
          continue;
        } else if (isset($tag['ol']) && '' !== $tag['ol']) {
          $lines->pushBack($line."\n");
          $result->append($this->enter(new ListContext('ol'))->parse($lines));
          $target= null;
          continue;
        } else if (isset($tag['blockquote']) && '' !== $tag['blockquote']) {
          $lines->pushBack($line."\n");
          $result->append($this->enter(new BlockquoteContext())->parse($lines));
          $target= null;
          continue;
        } else if (isset($tag['code']) && '' !== $tag['code']) {
          $lines->pushBack($line."\n");
          $result->append($this->enter(new CodeContext())->parse($lines));
          $target= null;
          continue;
        } else if (isset($tag['hr']) && '' !== $tag['hr']) {
          $result->append(new Ruler());
          continue;
        } else if (isset($tag['underline']) && '' !== $tag['underline']) {
          $paragraph= $result->last();
          $text= $paragraph->remove($paragraph->size() - 1);
          $result->append(new Header('=' === $tag['underline']{0} ? 1 : 2))->add($text);
          $target= null;
          continue;
        } else if (isset($tag['def']) && '' !== $tag['def']) {
          $title= trim(substr($line, strlen($tag[0])));
          if ('' !== $title && 0 === strcspn($title, '(\'"')) {
            $title= trim($title, $def[$title{0}]);
          } else {
            $title= null;
          }
          $result->urls[strtolower($tag[12])]= new Link($tag[13], null, $title);
          continue;
        }
        $line->forward(strlen($tag[0]));
      }

      // We got here, so there is more text, and no target -> we need to open
      // a new paragraph.
      if (null === $target) {
        $target= $result->append(new Paragraph());
      }

      // If previous line was text, add a newline
      // * Hello\nWorld -> <p>Hello\nWorld</p>
      // * Hello\n\nWorld -> <p>Hello</p><p>World</p>
      $last= $target->last();
      if ($last instanceof Text) {
        $last->value.= "\n";
      }

      $this->tokenizer->tokenize($line, $target);
    }

    // DEBUG \util\cmd\Console::writeLine($result);
    return $result;
  }

  /**
   * Returns this context's name
   *
   * @return string
   */
  public function name() {
    return ':top';
  }
}