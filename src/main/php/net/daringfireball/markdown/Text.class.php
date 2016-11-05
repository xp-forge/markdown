<?php namespace net\daringfireball\markdown;

/**
 * A fragment of text
 *
 * @test  xp://net.daringfireball.markdown.unittest.TextNodeTest
 */
class Text extends Node {
  public $value;

  /**
   * Creates a new fragment of text
   *
   * @param  string value
   */
  public function __construct($value= '') {
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
    return $emitter->emitText($this, $definitions);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $cmp
   * @return string
   */
  public function equals($cmp) {
    return $cmp instanceof self && $this->value === $cmp->value;
  }
}