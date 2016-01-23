<?php namespace net\daringfireball\markdown;

class Code extends Node {
  public function __construct($value) {
    $this->value= $value;
  }

  public function toString() {
    return nameof($this).'<'.$this->value.'>';
  }

  public function emit($definitions) {
    return '<code>'.htmlspecialchars($this->value).'</code>';
  }
}