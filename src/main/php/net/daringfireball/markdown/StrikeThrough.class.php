<?php namespace net\daringfireball\markdown;

class StrikeThrough extends NodeList {

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitStrikeThrough($this, $definitions);
  }
}