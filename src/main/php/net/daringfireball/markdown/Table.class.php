<?php namespace net\daringfireball\markdown;

/**
 * A table in markdown
 *
 * @see   http://www.tablesgenerator.com/markdown_tables
 * @test  xp://net.daringfireball.markdown.unittest.TableTest
 */
class Table extends NodeList {

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitTable($this, $definitions);
  }

  /** @return var[][] */
  public function rows() { return $this->nodes; }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    $s= nameof($this)."@{\n";
    foreach ($this->nodes as $row) {
      $s.= '  '.str_replace("\n", "\n  ", $row->toString())."\n";
    }
    return $s.'}';
  }
}