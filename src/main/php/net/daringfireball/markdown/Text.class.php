<?php namespace net\daringfireball\markdown;

/**
 * A fragment of text
 *
 * @test  xp://net.daringfireball.markdown.unittest.TextNodeTest
 */
class Text extends ValueNode {

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
}