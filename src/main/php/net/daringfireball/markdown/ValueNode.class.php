<?php namespace net\daringfireball\markdown;

abstract class ValueNode extends Node {
  public $value;

  /**
   * Creates a new box
   *
   * @param  var $value
   */
  public function __construct($value= '') {
    $this->value= $value;
  }


  /** @return string */
  public function toString() {
    return nameof($this).'<'.$this->value.'>';
  }

  /** @return string */
  public function hashCode() {
    return md5(static::class.$this->value);
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