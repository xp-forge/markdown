<?php namespace net\daringfireball\markdown;

abstract class NodeList extends Node {
  protected $nodes= array();

  public function size() {
    return sizeof($this->nodes);
  }

  public function add(Node $n) {
    $this->nodes[]= $n;
    return $n;
  }

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

  public function set($pos, Node $n) {
    $this->nodes[$pos]= $n;
    return $n;
  }

  public function get($pos) {
    return $this->nodes[$pos];
  }

  public function last() {
    $s= sizeof($this->nodes);
    return $s ? $this->nodes[$s - 1] : null;
  }

  public function toString() {
    return $this->getClassName().'<'.\xp::stringOf($this->nodes).'>';
  }

  public function emit($definitions) {
    $r= '';
    foreach ($this->nodes as $node) {
      $r.= $node->emit($definitions);
    }
    return $r;
  }
}