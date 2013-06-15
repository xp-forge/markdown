<?php namespace net\daringfireball\markdown;

/**
 * A block of code
 *
 * @test  xp://net.daringfireball.markdown.unittest.CodeTest 
 */
class CodeBlock extends NodeList {

  /**
   * Emit this code block
   *
   * @param	 [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    return '<code>'.parent::emit($definitions).'</code>';
  }
}