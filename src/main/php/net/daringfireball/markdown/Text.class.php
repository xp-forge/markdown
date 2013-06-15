<?php namespace net\daringfireball\markdown;

/**
 * A fragment of text
 *
 * @test  xp://net.daringfireball.markdown.unittest.TextNodeTest
 */
class Text extends Node {
  public $value;

  /**
   * Creates a new fragment of text
   *
   * @param  string value
   */
  public function __construct($value= '') {
    $this->value= $value;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'<'.$this->value.'>';
  }

  /**
   * Emit this text node
   *
   * @param  [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {

    // If the string ends with two or more spaces, we have a manual line break.
    $sp= 0;
    for ($i= strlen($this->value)- 1; $i > 0 && ' ' === $this->value{$i}; $i--) {
      $sp++;
    }
    if ($sp >= 2) {
      return htmlspecialchars(substr($this->value, 0, -$sp)).'<br/>';
    } else {
      return htmlspecialchars($this->value);
    }
  }
}