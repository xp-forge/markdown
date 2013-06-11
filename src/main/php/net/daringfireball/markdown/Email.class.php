<?php namespace net\daringfireball\markdown;

class Email extends Node {
  public function __construct($address) {
    $this->address= $address;
  }

  public function toString() {
    return $this->getClassName().'<'.$this->address.'>';
  }

  public function emit($definitions) {
    $encoded= '';
    for ($i= 0, $s= strlen($this->address); $i < $s; $i++) {
      $encoded.= '&#x'.dechex(ord($this->address{$i})).';';
    }

    // An encoded "mailto:" (with "i" and ":" in plain)
    return '<a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:'.$encoded.'">'.$encoded.'</a>';
  }
}