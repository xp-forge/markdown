<?php namespace net\daringfireball\markdown;

class Cell extends NodeList {
  private $type;

  /**
   * Creates a new list
   *
   * @param  string $type either "th" or "td"
   */
  public function __construct($type) {
    $this->type= $type;
  }

  /**
   * Emit this table row
   *
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    foreach ($this->nodes as $cell) {
      $r.= $cell->emit($definitions);
    }
    return '<'.$this->type.'>'.$r.'</'.$this->type.'>';
  }
}