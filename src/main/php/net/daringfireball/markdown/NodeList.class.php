<?php namespace net\daringfireball\markdown;

use util\Objects;

/**
 * Abstract base class for all nodes with nested child elements
 */
abstract class NodeList extends Node {
  protected $nodes;

  /**
   * Creates a new list of nodes
   *
   * @param  net.daringfireball.markdown.Node[] $nodes
   */
  public function __construct($nodes= []) {
    $this->nodes= $nodes;
  }

  /**
   * Returns this nodelist's size
   *
   * @return int
   */
  public function size() {
    return sizeof($this->nodes);
  }

  /**
   * Adds a child node
   *
   * @param  net.daringfireball.markdown.Node $n
   * @return net.daringfireball.markdown.Node The added node
   */
  public function add(Node $n) {
    $this->nodes[]= $n;
    return $n;
  }

  /**
   * Adds a child node. Optimizes away empty nodelists.
   *
   * @param  net.daringfireball.markdown.Node $n
   * @return net.daringfireball.markdown.Node The added node
   */
  public function append(Node $n) {
    $s= sizeof($this->nodes);

    // If last node is an empty nodelist, optimize it away, preventing 
    // output such as <p></p>
    if ($s && $this->nodes[$s - 1] instanceof self && 0 === sizeof($this->nodes[$s - 1]->nodes)) {
      $this->nodes[$s - 1]= $n;
    } else {
      $this->nodes[]= $n;
    }
    return $n;
  }

  /**
   * Sets a node at a given position.
   *
   * @param  int $pos
   * @param  net.daringfireball.markdown.Node $n
   * @return net.daringfireball.markdown.Node The given node
   */
  public function set($pos, Node $n) {
    $this->nodes[$pos]= $n;
    return $n;
  }

  /**
   * Gets a node at a given position.
   *
   * @param  int $pos
   * @return net.daringfireball.markdown.Node The added node
   */
  public function get($pos) {
    return $this->nodes[$pos];
  }

  /**
   * Gets all nodes
   *
   * @return net.daringfireball.markdown.Node[]
   */
  public function all() {
    return $this->nodes;
  }

  /**
   * Removes a node at a given position
   *
   * @param  int $pos
   * @return net.daringfireball.markdown.Node The removed node
   */
  public function remove($pos) {
    $candidate= $this->nodes[$pos];
    unset($this->nodes[$pos]);
    return $candidate;
  }

  /**
   * Gets the last node
   *
   * @return net.daringfireball.markdown.Node ...or NULL if this list is empty
   */
  public function last() {
    $s= sizeof($this->nodes);
    return $s ? $this->nodes[$s - 1] : null;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'<'.\xp::stringOf($this->nodes).'>';
  }

  /**
   * Helper method to emit all nodes
   *
   * @param  net.daringfireball.markdown.Node[] $nodes
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  protected function emitAll($nodes, $definitions) {
    $r= '';
    foreach ($nodes as $node) {
      $r.= $node->emit($definitions);
    }
    return $r;
  }

  /**
   * Emit this list
   *
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($definitions) {
    return $this->emitAll($this->nodes, $definitions);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $cmp
   * @return string
   */
  public function equals($cmp) {
    return $cmp instanceof self && Objects::equal($this->nodes, $cmp->nodes);
  }
}