<?php namespace net\daringfireball\markdown;

/**
 * Abstract base class for all nodes with nested child elements
 */
abstract class NodeList extends Node {
  protected $nodes= array();

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
    return $this->getClassName().'<'.\xp::stringOf($this->nodes).'>';
  }

  /**
   * Emit this list
   *
   * @param  [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    foreach ($this->nodes as $node) {
      $r.= $node->emit($definitions);
    }
    return $r;
  }
}