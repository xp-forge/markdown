<?php namespace net\daringfireball\markdown;

class InlineTableContext extends TableContext {

  /**
   * Parse a line into a cells
   *
   * @param  string $line
   * @return string[]
   */
  protected function cellsIn($line) {
    if (preg_match('/^(.+\|.+)+$/', $line)) {
      return explode('|', $line);
    } else {
      return null;
    }
  }
}