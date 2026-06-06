<?php namespace net\daringfireball\markdown;

class BlockquoteContext extends Context {

  /**
   * Parse input into nodes
   *
   * @param  net.daringfireball.markdown.Input $lines
   * @return net.daringfireball.markdown.Node
   */
  public function parse($lines) {
    $nesting= 1;
    $target= [new BlockQuote()];
    while ($lines->hasMoreLines()) {
      $line= $lines->nextLine();

      // Handle nested quotes
      $str= $line->str();
      $start= strspn($str, '> ');
      $level= $start ? substr_count($str, '>', 0, $start) : 0;

      if (0 === $level) {
        $line->forward(-$line->pos());
        $lines->resetLine($line);
        break;
      }

      while ($level > $nesting) {
        array_unshift($target, $target[0]->add(new BlockQuote()));
        $nesting++;
      }
      while ($level < $nesting) {
        array_shift($target);
        $nesting--;
      }

      // Check handlers
      $handled= false;
      $lines->indent(+$start);
      $line->forward($start);

      foreach ($this->handlers as $pattern => $handler) {
        if (preg_match($pattern, $line->str(), $values)) {
          if ($handled= $handler($lines, [$line] + $values, $target[0], $this)) break;
        }
      }

      $lines->indent(-$start);
      $handled || $this->tokenize($line, $target[0]);
    }

    return array_pop($target);
  }

  /**
   * Returns this context's name
   *
   * @return string
   */
  public function name() {
    return 'blockquote';
  }
}