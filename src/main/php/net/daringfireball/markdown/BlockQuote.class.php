<?php namespace net\daringfireball\markdown;

class BlockQuote extends NodeList {

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return '<blockquote>'.parent::emit($emitter, $definitions).'</blockquote>';
  }
}