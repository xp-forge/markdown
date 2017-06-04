<?php namespace net\daringfireball\markdown;

class Code extends Node {
  public $value;

  /**
   * Creates a new code element
   *
   * @param string $value
   */
  public function __construct($value) {
    $this->value= $value;
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitCode($this, $definitions);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'<'.$this->value.'>';
  }

  /** @return string */
  public function hashCode() {
    return '`'.md5($this->value);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) {
    return $value instanceof self ? strcmp($this->value, $value->value) : 1;
  }
}