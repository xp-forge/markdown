<?php namespace net\daringfireball\markdown;

abstract class TableContext extends Context {
  private $headers, $alignment;

  /**
   * Creates a new table context
   *
   * @param  string $headers
   * @param  string $alignment
   */
  public function __construct($headers, $alignment) {
    $this->headers= $headers;
    $this->alignment= [];
    foreach ($this->cellsIn($alignment) as $align) {
      preg_match('/^ ?(:)?\-+(:)? ?$/', $align, $matches);
      if (isset($matches[2])) {
        $this->alignment[]= $matches[1] ? 'center' : 'right';
      } else if (isset($matches[1])) {
        $this->alignment[]= 'left';
      } else {
        $this->alignment[]= null;
      }
    }
  }

  /**
   * Parse a line into a cells. Returns NULL if this is no table line.
   *
   * @param  string $line
   * @return string[]
   */
  protected abstract function cellsIn($line);

  /**
   * Parse a line into a row
   *
   * @param  string $line
   * @param  string $line
   * @return net.daringfireball.markdown.Row
   */
  private function parseRow($line, $type) {
    if (null === ($cells= $this->cellsIn($line))) return null;

    $row= new Row();
    foreach ($cells as $pos => $cell) {
      $this->tokenize(new Line(trim($cell)), $row->add(new Cell($type, $this->alignment[$pos] ?? null)));
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
    $table->add($this->parseRow($this->headers, 'th'));

    while ($lines->hasMoreLines()) {
      $line= $lines->nextLine();
      if (null === ($row= $this->parseRow($line, 'td'))) {
        $lines->resetLine($line);
        break;
      }
      $table->add($row);
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