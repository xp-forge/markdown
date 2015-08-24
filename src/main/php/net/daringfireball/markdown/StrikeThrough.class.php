<?php namespace net\daringfireball\markdown;

class StrikeThrough extends NodeList {

  public function emit($definitions) {
    return '<del>'.parent::emit($definitions).'</del>';
  }
}