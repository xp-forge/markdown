<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Line;

/**
 * Base class for Input implementation tests.
 */
abstract class InputTest extends \unittest\TestCase {

  /**
   * Create and return new fixture.
   *
   * @param   string source
   * @return  net.daringfireball.markdown.Input
   */
  protected abstract function newFixture($source);

  #[@test]
  public function empty_input() {
    $this->assertEquals(null, $this->newFixture('')->nextLine());
  }

  #[@test, @values(array("Test\n", "Test\r\n", "Test\r"))]
  public function one_line($value) {
    $this->assertEquals(new Line('Test'), $this->newFixture($value)->nextLine());
  }

  #[@test, @values(array("Line 1\nLine 2\n", "Line 1\r\nLine 2\r\n", "Line 1\rLine 2\r"))]
  public function two_lines($value) {
    $fixture= $this->newFixture($value);
    $this->assertEquals(
      array(new Line('Line 1'), new Line('Line 2')),
      array($fixture->nextLine(), $fixture->nextLine())
    );
  }
}