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
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'<'.$this->value.'>';
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
}