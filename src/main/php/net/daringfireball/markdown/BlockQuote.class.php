<?php namespace net\daringfireball\markdown;

class BlockQuote extends NodeList {

  public function emit($definitions) {
    return '<blockquote>'.parent::emit($definitions).'</blockquote>';
  }
}