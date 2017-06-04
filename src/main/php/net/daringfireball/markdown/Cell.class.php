<?php namespace net\daringfireball\markdown;

use util\Objects;

class Cell extends NodeList {
  public $type, $alignment;

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
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitCell($this, $definitions);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'(type= '.$this->type.', alignment= '.$this->alignment.')@'.Objects::stringOf($this->nodes);
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