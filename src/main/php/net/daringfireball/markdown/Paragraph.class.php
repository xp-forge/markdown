<?php namespace net\daringfireball\markdown;

/**
 * A paragraph
 *
 * @test  xp://net.daringfireball.markdown.unittest.ParagraphTest 
 */
class Paragraph extends NodeList {

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitParagraph($this, $definitions);
  }
}