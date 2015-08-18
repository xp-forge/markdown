<?php namespace net\daringfireball\markdown;

class WrappedTableContext extends TableContext {

  /**
   * Parse a line into a cells
   *
   * @param  string $line
   * @return string[]
   */
  protected function cellsIn($line) {
    if ('|' === $line{0}) {
      return explode('|', substr($line, 1, -1));
    } else {
      return null;
    }
  }
}