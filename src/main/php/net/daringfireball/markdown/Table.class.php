<?php namespace net\daringfireball\markdown;

/**
 * A table in markdown
 *
 * @see   http://www.tablesgenerator.com/markdown_tables
 * @test  xp://net.daringfireball.markdown.unittest.TableTest
 */
class Table extends NodeList {
 
  /**
   * Emit this table
   *
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    foreach ($this->nodes as $row) {
      $r.= $row->emit($definitions);
    }
    return '<table>'.$r.'</table>';
  }

  /** @return var[][] */
  public function rows() {
    $rows= [];
    foreach ($this->nodes as $i => $row) {
      $i && $rows[]= $row->cells();
    }
    return $rows;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    $s= nameof($this)."@{\n";
    foreach ($this->nodes as $row) {
      $s.= '  '.$row->toString()."\n";
    }
    return $s.'}';
  }
}