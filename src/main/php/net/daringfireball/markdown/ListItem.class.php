<?php namespace net\daringfireball\markdown;

class ListItem extends NodeList {
  public $paragraphs;

  public function emit($definitions) {
    return 
      '<li>'.
      ($this->paragraphs ? '<p>' : '').
      parent::emit($definitions).
      ($this->paragraphs ? '</p>' : '').
      '</li>'
     ;
  }
}