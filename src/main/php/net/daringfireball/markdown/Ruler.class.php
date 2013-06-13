<?php namespace net\daringfireball\markdown;

class Ruler extends Node {

  public function emit($definitions) {
    return '<hr/>';
  }
}