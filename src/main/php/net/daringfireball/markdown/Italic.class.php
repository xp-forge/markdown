<?php namespace net\daringfireball\markdown;

class Italic extends NodeList {

  public function emit($definitions) {
    return '<em>'.parent::emit($definitions).'</em>';
  }
}