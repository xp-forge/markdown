<?php namespace net\daringfireball\markdown;

class Entity extends Node {
  public function __construct($value) {
    $this->value= $value;
  }

  public function toString() {
    return nameof($this).'<'.$this->value.'>';
  }

  public function emit($definitions) {
    return $this->value;
  }
}