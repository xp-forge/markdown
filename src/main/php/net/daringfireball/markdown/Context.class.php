<?php namespace net\daringfireball\markdown;

abstract class Context extends \lang\Object {
  protected $parent= null;
  public $tokenizer;

  public function enter(self $context) {
    $context->parent= $this;
    $context->tokenizer= $this->tokenizer;
    return $context;
  }

  public abstract function parse($line);

  /**
   * Returns this context's name
   *
   * @return string
   */
  public abstract function name();

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