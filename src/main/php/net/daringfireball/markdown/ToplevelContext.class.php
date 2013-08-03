<?php namespace net\daringfireball\markdown;

class ToplevelContext extends Context {

  public function setHandlers($handlers) {
    $this->handlers= $handlers;
  }

  /**
   * Parse input into nodes
   *
   * @param  net.daringfireball.markdown.Input $lines
   * @return net.daringfireball.markdown.Node
   */
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
    while ($lines->hasMoreLines()) {
      $line= $lines->nextLine();

      // An empty line by itself ends the last element and starts a new
      // paragraph (if there are any more lines)
      if (0 === $line->length()) {
        $target= null;
      }

      // Check handlers
      $handled= false;
      foreach ($this->handlers as $pattern => $handler) {
        if (preg_match($pattern, $line, $values)) {
          $handled= $handler($lines, $values, $result, $this);
          break;
        }
      }

      if ($handled) {
        $target= null;
        continue;
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

      $this->tokenize($line, $target);
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