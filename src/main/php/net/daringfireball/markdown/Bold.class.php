<?php namespace net\daringfireball\markdown;

class Bold extends NodeList {

  public function emit($definitions) {
    return '<strong>'.parent::emit($definitions).'</strong>';
  }
}