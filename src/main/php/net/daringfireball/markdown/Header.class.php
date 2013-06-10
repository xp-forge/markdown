<?php namespace net\daringfireball\markdown;

class Header extends NodeList {
  public function __construct($level) {
    $this->level= $level;
  }

  public function toString() {
    return $this->getClassName().'(h'.$this->level.')<'.\xp::stringOf($this->nodes).'>';
  }

  public function emit($definitions) {
    return '<h'.$this->level.'>'.parent::emit($definitions).'</h'.$this->level.'>';
  }
}