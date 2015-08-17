<?php namespace net\daringfireball\markdown;

class TableContext extends Context {

  /**
   * Parse a line into a row
   *
   * @param  string $line
   * @param  string $line
   * @return net.daringfireball.markdown.Row
   */
  private function parseRow($line, $type) {
    $row= new Row($type);
    foreach (explode('|', substr($line, 1, -1)) as $cell) {
      $row->add(new Text(trim($cell)));
    }
    return $row;
  }

  /**
   * Parse input into nodes
   *
   * @param  net.daringfireball.markdown.Input $lines
   * @return net.daringfireball.markdown.Node
   */
  public function parse($lines) {
    $table= new Table();
    $table->add($this->parseRow($lines->nextLine(), 'th'));
    $lines->nextLine();

    while ($lines->hasMoreLines()) {
      $line= $lines->nextLine();
      if ('|' === $line{0}) {
        $table->add($this->parseRow($line, 'td'));
      } else {
        break;
      }
    }

    return $table;
  }

  /**
   * Returns this context's name
   *
   * @return string
   */
  public function name() {
    return 'table';
  }
}