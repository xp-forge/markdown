<?php namespace net\daringfireball\markdown;

class Email extends Node {
  public $address;

  /**
   * Creates a new email node
   *
   * @param  string $address
   */
  public function __construct($address) {
    $this->address= $address;
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitEmail($this, $definitions);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'<'.$this->address.'>';
  }

  /** @return string */
  public function hashCode() {
    return '@'.md5($this->address);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) {
    return $value instanceof self ? strcmp($this->address, $value->address) : 1;
  }
}