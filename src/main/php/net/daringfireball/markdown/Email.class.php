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
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'<'.$this->address.'>';
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
}