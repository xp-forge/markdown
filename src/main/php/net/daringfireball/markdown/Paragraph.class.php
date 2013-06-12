<?php namespace net\daringfireball\markdown;

class Paragraph extends NodeList {

  public function emit($definitions) {
    return '<p>'.parent::emit($definitions).'</p>';
  }
}