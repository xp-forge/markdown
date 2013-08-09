<?php namespace net\daringfireball\markdown;

/**
 * Input implementation for strings
 *
 * @test  xp://net.daringfireball.markdown.unittest.StringInputTest
 */
class StringInput extends Input {
  protected $str= '';
  protected $offset= 0;

  /**
   * Creates a new string input instance
   *
   * @param  string str
   */
  public function __construct($str) {
    $this->str= $str;
    $this->offset= 0;
  }

  /**
   * Reads a line
   *
   * @return string or NULL to indicate EOF
   */
  protected function readLine() {
    if ($this->offset >= strlen($this->str)) return null;

    // Find \r or \n, which ever occurs first. Check for \r\n
    $c= strcspn($this->str, "\r\n", $this->offset);
    $s= 1 + ("\r\n" === substr($this->str, $this->offset + $c, 2));

    // Return slice
    $line= substr($this->str, $this->offset, $c);
    $this->offset+= $c + $s;
    return $line;
  }

  /**
   * Returns a description of the source for use in `toString()`
   *
   * @return string
   */
  protected function sourceDescription() {
    return '<'.strlen($this->str).' byte string>';
  }
}