<?php namespace net\daringfireball\markdown;

/**
 * Abstract base class for input
 */
abstract class Input extends \lang\Object {
  protected $stack= array();

  /**
   * Reads a line
   *
   * @return string or NULL to indicate EOF
   */
  protected abstract function readLine();

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
    return array_pop($this->stack);
  }

  /**
   * Pushes back a line
   *
   * @param  net.daringfireball.markdown.Line $line
   */
  public function resetLine($line) {
    $this->stack[]= $line;
  }
}