<?php namespace net\daringfireball\markdown;

class ListItem extends NodeList {

  public function emit($definitions) {
    return '<li>'.parent::emit($definitions).'</li>';
  }
}