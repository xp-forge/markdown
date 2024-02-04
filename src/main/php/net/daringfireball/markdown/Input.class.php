<?php namespace net\daringfireball\markdown;

use io\streams\TextReader;
use util\Objects;
use lang\Value;

/** Abstract base class for input */
abstract class Input implements Value {
  protected $stack= [];
  protected $line= 1;
  public $indent= 0;

  /**
   * Creates a new input from a given argument
   *
   * @param  string|self|io.streams.TextReader $arg
   * @return self
   */
  public static function from($arg) {
    if ($arg instanceof self) {
      return $arg;
    } else if ($arg instanceof TextReader) {
      return new ReaderInput($arg);
    } else {
      return new StringInput((string)$arg);
    }
  }

  /**
   * Returns current line numer
   *
   * @return int
   */
  public function currentLine() {
    return $this->line;
  }

  /**
   * Sets indent
   *
   * @param  int $delta
   * @return void
   */
  public function indent($delta) {
    $this->indent+= $delta;
  }

  /**
   * Reads a line
   *
   * @return string or NULL to indicate EOF
   */
  protected abstract function readLine();

  /**
   * Returns a description of the source for use in `toString()`
   *
   * @return string
   */
  protected abstract function sourceDescription();

  /**
   * Returns whether there are more lines
   *
   * @return bool
   */
  public function hasMoreLines() {
    if (empty($this->stack)) {
      $l= $this->readLine();
      if (null === $l) return false;
      $this->stack[]= new Line($l);

    }
    return true;
  }

  /**
   * Returns next line
   *
   * @return net.daringfireball.markdown.Line
   */
  public function nextLine() {
    if (empty($this->stack)) {
      if (!$this->hasMoreLines()) return null;
    }
    $this->line++;
    $line= array_pop($this->stack);
    return $this->indent ? $line->indented($this->indent) : $line;
  }

  /**
   * Pushes back a line
   *
   * @param  ?string|net.daringfireball.markdown.Line $line
   */
  public function resetLine($line) {
    if (null === $line) return;
    $this->stack[]= $line instanceof Line ? $line : new Line($line);
    $this->line--;
  }

  /**
   * Creates a string representation of this input
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'(line '.$this->line.' of '.$this->sourceDescription().')';
  }

  /** @return string */
  public function hashCode() {
    return md5($this->line.'@'.$this->sourceDescription());
  }

  /**
   * Compare
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare(
        [$this->line, $this->sourceDescription()],
        [$value->line, $value->sourceDescription()]
      )
      : 1
    ;
  }
}