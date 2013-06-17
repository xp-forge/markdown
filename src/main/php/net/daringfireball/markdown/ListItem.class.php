<?php namespace net\daringfireball\markdown;

class ListItem extends NodeList {
  public $paragraphs;

  /**
   * Emit this text node
   *
   * @param  [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    if ($this->paragraphs) {
      return '<li>'.parent::emit($definitions).'</li>';
    } else {
      return '<li>'.$this->emitAll($this->nodes[0]->nodes, $definitions).'</li>';
    }
  }
}