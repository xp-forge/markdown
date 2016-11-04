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
   * Emit this list
   *
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($definitions) {
    return $this->emitAll($this->nodes, $this->urls + $definitions);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    $s= '';
    foreach ($this->nodes as $node) {
      $s.= '  '.str_replace("\n", "\n  ", $node->toString())."\n";
    }
    return nameof($this)."@{\n".
      "urls  : ".\xp::stringOf($this->urls)."\n".
      "nodes : [\n".$s."]\n".
    "}";
  }

  /**
   * Returns whether a given comparison value is equal to this parse tree
   *
   * @param  var $cmp
   * @return string
   */
  public function equals($cmp) {
    return parent::equals($cmp) && Objects::equal($this->urls, $cmp->urls);
  }
}