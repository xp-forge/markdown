<?php namespace net\daringfireball\markdown;

abstract class Context extends \lang\Object {
  protected $tokens= array();
  protected $span= '';

  public function enter(self $context) {
    $context->tokens= $this->tokens;
    return $context;
  }

  public function setTokens($tokens) {
    $this->tokens= $tokens;
    $this->span= '\\'.implode('', array_keys($tokens));
  }

  /**
   * Parse a node from a given input
   *
   * @param  var lines
   * @return net.daringfireball.markdown.Node The parsed npde
   */
  public abstract function parse($lines);

  /**
   * Tokenize a line
   *
   * @param  net.daringfireball.markdown.Line $l The line
   * @param  net.daringfireball.markdown.Node $target The target node to add nodes to
   * @return net.daringfireball.markdown.Node The target
   */
  public function tokenize(Line $line, Node $target) {
    $safe= 0;
    $l= $line->length();
    while ($line->pos() < $l) {
      $t= '';
      $c= $line->chr();
      if ('\\' === $c) {
        $t= $line{$line->pos() + 1};
        $line->forward(2);          // Skip escape, don't tokenize next character
      } else if (isset($this->tokens[$c])) {
        if (!$this->tokens[$c]($line, $target, $this)) {
          $t= $c;                   // Push back
          $line->forward();
        }
      }

      $target->add(new Text($t.$line->until($this->span)));
      if ($safe++ > $l) throw new \lang\IllegalStateException('Endless loop detected');
    }
    return $target;
  }


  /**
   * Returns this context's name
   *
   * @return string
   */
  public abstract function name();

  /**
   * Creates a string representation of this context
   *
   * @return string
   */
  public function toString() {
    $s= 'net.daringfireball.markdown.Context('.$this->name();
    $parent= $this->parent;
    while (null !== $parent) {
      $s.= ' > '.$parent->name();
      $parent= $parent->parent;
    }
    return $s.')';
  }
}