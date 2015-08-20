<?php namespace net\daringfireball\markdown;

class Row extends NodeList {

  /** @return var[][] */
  public function cells() { return $this->nodes; }

  /**
   * Emit this table row
   *
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    foreach ($this->nodes as $cell) {
      $r.= $cell->emit($definitions);
    }
    return '<tr>'.$r.'</tr>';
  }
}