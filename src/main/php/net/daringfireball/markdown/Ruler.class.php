<?php namespace net\daringfireball\markdown;

class Ruler extends Node {

  public function emit($definitions) {
    return '<hr/>';
  }

  /** @return string */
  public function toString() {
    return nameof($this);
  }

  /** @return string */
  public function hashCode() {
    return '---';
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) {
    return $value instanceof self ? 0 : 1;
  }
}