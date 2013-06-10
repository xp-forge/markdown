<?php namespace net\daringfireball\markdown;

class Bold extends Node {
  public function __construct($value) {
    $this->value= $value;
  }

  public function toString() {
    return $this->getClassName().'<'.$this->value.'>';
  }

  public function emit($definitions) {
    return '<strong>'.htmlspecialchars($this->value).'</strong>';
  }
}