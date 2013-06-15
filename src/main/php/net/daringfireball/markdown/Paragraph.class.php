<?php namespace net\daringfireball\markdown;

/**
 * A paragraph
 *
 * @test  xp://net.daringfireball.markdown.unittest.ParagraphTest 
 */
class Paragraph extends NodeList {

  /**
   * Emit this paragraph
   *
   * @param	 [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    return '<p>'.parent::emit($definitions).'</p>';
  }
}