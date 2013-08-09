<?php namespace net\daringfireball\markdown;

/**
 * Abstract base class for input
 */
abstract class Input extends \lang\Object {
  protected $stack= array();
  protected $line= 1;

  /**
   * Returns current line numer
   *
   * @return int
   */
  public function currentLine() {
    return $this->line;
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
    return array_pop($this->stack);
  }

  /**
   * Pushes back a line
   *
   * @param  net.daringfireball.markdown.Line $line
   */
  public function resetLine($line) {
    $this->stack[]= $line;
    $this->line--;
  }

  /**
   * Creates a string representation of this input
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'(line '.$this->line.' of '.$this->sourceDescription().')';
  }
}