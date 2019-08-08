<?php namespace net\daringfireball\markdown;

class Header extends NodeList {
  public $level;

  /**
   * Creates a new node level
   *
   * @param int $level
   * @param  net.daringfireball.markdown.Node[] $nodes
   */
  public function __construct($level, $nodes= []) {
    $this->level= $level;
    parent::__construct($nodes);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(h'.$this->level.')<'.$this->nodesIndented('  ').'>';
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitHeader($this, $definitions);
  }
}