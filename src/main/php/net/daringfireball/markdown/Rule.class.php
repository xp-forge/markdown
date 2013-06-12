<?php namespace net\daringfireball\markdown;

class Rule extends Node {

  public function emit($definitions) {
    return '<hr/>';
  }
}