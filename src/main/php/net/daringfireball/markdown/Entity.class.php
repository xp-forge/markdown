<?php namespace net\daringfireball\markdown;

/**
 * A HTML entity
 *
 * @test  xp://net.daringfireball.markdown.unittest.EntityTest
 */
class Entity extends ValueNode {

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitEntity($this, $definitions);
  }
}