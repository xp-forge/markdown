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

  public function set($pos, Node $n) {
    $this->nodes[$pos]= $n;
    return $n;
  }

  public function get($pos) {
    return $this->nodes[$pos];
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