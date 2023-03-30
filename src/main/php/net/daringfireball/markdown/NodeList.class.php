<?php namespace net\daringfireball\markdown;

use util\Objects;

/**
 * Base class for all nodes with nested child elements
 *
 * @test  xp://net.daringfireball.markdown.unittest.NodeListTest
 */
class NodeList extends Node {
  protected $nodes= [];

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
   * @return net.daringfireball.markdown.Node
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
   * Gets a slice of the node nodes
   *
   * @param  int $offset
   * @return net.daringfireball.markdown.Node[]
   */
  public function slice($offset) {
    return array_slice($this->nodes, $offset);
  }

  /**
   * Removes a node at a given position
   *
   * @param  int $pos
   * @return net.daringfireball.markdown.Node The removed node, or NULL if there was no node
   */
  public function remove($pos) {
    if ($pos < 0 || $pos >= sizeof($this->nodes)) return null;

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
   * Emit this parse tree
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitNodeList($this, $definitions);
  }

  /**
   * Returns nodes' string representation indented with a given indent
   *
   * @param  string $indent
   * @return string
   */
  protected function nodesIndented($indent) {
    if (empty($this->nodes)) return '';

    $s= "\n";
    foreach ($this->nodes as $node) {
      $s.= $indent.str_replace("\n", "\n".$indent, $node->toString())."\n";
    }
    return $s;
  }

  /** @return string */
  public function toString() {
    return nameof($this).'@{'.$this->nodesIndented('  ').'}';
  }

  /** @return string */
  public function hashCode() {
    return '['.Objects::hashOf($this->nodes);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) {
    return $value instanceof self ? Objects::compare($this->nodes, $value->nodes) : 1;
  }
}