<?php namespace net\daringfireball\markdown;

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

  /** @return string[][] */
  public function rows() {
    $rows= [];
    foreach ($this->nodes as $row) {
      $rows[]= array_map(function($cell) { return $cell->value; }, $row->nodes);
    }
    return $rows;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    $s= $this->getClassName()."@{\n";
    foreach ($this->nodes as $row) {
      $s.= '  '.$row->toString()."\n";
    }
    return $s.'}';
  }
}