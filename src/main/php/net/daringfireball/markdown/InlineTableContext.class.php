<?php namespace net\daringfireball\markdown;

class InlineTableContext extends TableContext {

  /**
   * Parse a line into a cells
   *
   * @param  string $line
   * @return string[]
   */
  protected function cellsIn($line) {
    $str= $line->str();
    if (preg_match('/^(.+\|.+)+$/', $str)) {
      return explode('|', $str);
    } else {
      return null;
    }
  }
}