<?php namespace net\daringfireball\markdown;

class ParseTree extends NodeList {
  public $urls= array();

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName()."@{\n".
      "urls  : ".\xp::stringOf($this->urls)."\n".
      "nodes : ".\xp::stringOf($this->nodes)."\n".
    "}";
  }
}