<?php namespace net\daringfireball\markdown;

use util\Objects;

class ParseTree extends NodeList {
  public $urls= [];

  /**
   * Creates a new list of nodes
   *
   * @param  net.daringfireball.markdown.Node[] $nodes
   * @param  [:net.daringfireball.markdown.Link] $urls
   */
  public function __construct($nodes= [], $urls= []) {
    parent::__construct($nodes);
    $this->urls= $urls;
  }

  /**
   * Emit this parse tree
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitParseTree($this, $definitions);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    $s= '';
    foreach ($this->nodes as $node) {
      $s.= '    '.str_replace("\n", "\n    ", $node->toString())."\n";
    }
    return nameof($this)."@{\n".
      "  urls  : ".Objects::stringOf($this->urls)."\n".
      "  nodes : [\n".$s."  ]\n".
    "}";
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([$this->urls, $this->nodes], [$value->urls, $value->nodes])
      : 1
    ;
  }
}