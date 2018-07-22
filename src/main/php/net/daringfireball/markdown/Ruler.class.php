<?php namespace net\daringfireball\markdown;

/**
 * A (horizontal) ruler
 *
 * @test  xp://net.daringfireball.markdown.unittest.RulerTest
 */
class Ruler extends Node {

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitRuler($this, $definitions);
  }

  /** @return string */
  public function toString() { return nameof($this); }

  /** @return string */
  public function hashCode() { return '---'; }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) { return $value instanceof self ? 0 : 1; }
}