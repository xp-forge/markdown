<?php namespace net\daringfireball\markdown;

class Entity extends Node {
  public function __construct($value) {
    $this->value= $value;
  }

  public function toString() {
    return $this->getClassName().'<'.$this->value.'>';
  }

  public function emit($definitions) {
    return $this->value;
  }
}