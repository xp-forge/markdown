<?php namespace net\daringfireball\markdown;

abstract class Context extends \lang\Object {
  public $tokenizer;

  public function enter(self $context) {
    $context->tokenizer= $this->tokenizer;
    return $context;
  }

  /**
   * Parse a node from a given input
   *
   * @param  var lines
   * @return net.daringfireball.markdown.Node The parsed npde
   */
  public abstract function parse($lines);

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