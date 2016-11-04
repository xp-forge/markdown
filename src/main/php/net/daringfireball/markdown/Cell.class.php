<?php namespace net\daringfireball\markdown;

class Cell extends NodeList {
  private $type, $alignment;

  /**
   * Creates a new list
   *
   * @param  string $type either "th" or "td"
   * @param  string $alignment
   */
  public function __construct($type, $alignment) {
    $this->type= $type;
    $this->alignment= $alignment;
  }

  /**
   * Emit this table row
   *
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    foreach ((array)$this->nodes as $cell) {
      $r.= $cell->emit($definitions);
    }
    $attr= $this->alignment ? ' style="text-align: '.$this->alignment.'"' : '';
    return '<'.$this->type.$attr.'>'.$r.'</'.$this->type.'>';
  }
}