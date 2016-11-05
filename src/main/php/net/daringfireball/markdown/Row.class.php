<?php namespace net\daringfireball\markdown;

class Row extends NodeList {

  /** @return var[][] */
  public function cells() { return $this->nodes; }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitRow($this, $definitions);
  }
}