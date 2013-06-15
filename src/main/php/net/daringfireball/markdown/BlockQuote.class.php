<?php namespace net\daringfireball\markdown;

class BlockQuote extends NodeList {

  /**
   * Emit this blockquote element
   *
   * @param	 [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    return '<blockquote>'.parent::emit($definitions).'</blockquote>';
  }
}