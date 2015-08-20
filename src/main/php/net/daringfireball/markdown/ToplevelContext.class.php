<?php namespace net\daringfireball\markdown;

class ToplevelContext extends Context {

  /**
   * Sets handlers
   * 
   * @param [:var] handlers
   * @see   xp://net.daringfireball.markdown.Markdown#addHandler
   */
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
          if ($handled= $handler($lines, [$line] + $values, $result, $this)) {
            $target= null;
            break;
          }
        }
      }
      if ($handled) continue;

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