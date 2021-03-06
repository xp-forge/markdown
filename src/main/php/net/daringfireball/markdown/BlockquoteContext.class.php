<?php namespace net\daringfireball\markdown;

class BlockquoteContext extends Context {
  public $handlers;

  /**
   * Creates a new block quote context
   *
   * @param  [:var] $handlers
   */
  public function __construct($handlers) {
    $this->handlers= $handlers;
  }

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
      $start= strspn($line, '> ');
      $level= substr_count($line, '>', 0, $start);

      if (0 === $level) break;

      while ($level > $nesting) {
        array_unshift($target, $target[0]->add(new BlockQuote()));
        $nesting++;
      }
      while ($level < $nesting) {
        array_shift($target);
        $nesting--;
      }

      // Check handlers
      $quoted= new Line(substr($line, $start));
      foreach ($this->handlers as $pattern => $handler) {
        if (preg_match($pattern, $quoted, $values)) {
          if ($handled= $handler($lines, [$quoted] + $values, $target[0], $this)) continue 2;
        }
      }

      $this->tokenize($quoted, $target[0]);
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