<?php namespace net\daringfireball\markdown;

class Row extends NodeList {
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
      $r.= '<'.$this->type.'>'.$cell->emit($definitions).'</'.$this->type.'>';
    }
    return '<tr>'.$r.'</tr>';
  }
}