<?php namespace net\daringfireball\markdown;

use util\Objects;

class Header extends NodeList {
  public $level;

  /** @param int $level */
  public function __construct($level) {
    $this->level= $level;
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(h'.$this->level.')<'.Objects::stringOf($this->nodes).'>';
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return '<h'.$this->level.'>'.parent::emit($emitter, $definitions).'</h'.$this->level.'>';
  }
}