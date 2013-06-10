<?php namespace net\daringfireball\markdown;

class Listing extends NodeList {
  public function __construct($type) {
    $this->type= $type;
  }

  public function toString() {
    return $this->getClassName().'('.$this->type.')<'.\xp::stringOf($this->nodes).'>';
  }

  public function emit($definitions) {
    return '<'.$this->type.'>'.parent::emit($definitions).'</'.$this->type.'>';
  }
}