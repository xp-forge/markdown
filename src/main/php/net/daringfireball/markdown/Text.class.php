<?php namespace net\daringfireball\markdown;

class Text extends Node {
  public function __construct($value) {
    $this->value= $value;
  }

  public function toString() {
    return $this->getClassName().'<'.$this->value.'>';
  }

  public function emit($definitions) {
    return htmlspecialchars($this->value);
  }
}