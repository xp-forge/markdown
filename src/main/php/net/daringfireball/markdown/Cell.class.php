<?php namespace net\daringfireball\markdown;

use util\Objects;

class Cell extends NodeList {
  private $type, $alignment;

  /**
   * Creates a new list
   *
   * @param  string $type either "th" or "td"
   * @param  string $alignment
   * @param  net.daringfireball.markdown.Node[] $nodes
   */
  public function __construct($type, $alignment, $nodes= []) {
    $this->type= $type;
    $this->alignment= $alignment;
    parent::__construct($nodes);
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
    $attr= $this->alignment ? ' style="text-align: '.$this->alignment.'"' : '';
    return '<'.$this->type.$attr.'>'.$r.'</'.$this->type.'>';
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'(type= '.$this->type.', alignment= '.$this->alignment.')@'.\xp::stringOf($this->nodes);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $cmp
   * @return string
   */
  public function equals($cmp) {
    return (
      $cmp instanceof self &&
      $this->type === $cmp->type &&
      $this->alignment === $cmp->alignment &&
      Objects::equal($this->nodes, $cmp->nodes)
    );
  }
}