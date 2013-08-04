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
  public function has_empty_input() {
    $this->assertFalse($this->newFixture('')->hasMoreLines());
  }

  #[@test]
  public function has_empty_input_twice() {
    $fixture= $this->newFixture('');
    $this->assertFalse($fixture->hasMoreLines(), '#1');
    $this->assertFalse($fixture->hasMoreLines(), '#2');
  }

  #[@test]
  public function read_empty_input() {
    $this->assertNull($this->newFixture('')->nextLine());
  }

  #[@test]
  public function read_empty_input_twice() {
    $fixture= $this->newFixture('');
    $this->assertNull($fixture->nextLine(), '#1');
    $this->assertNull($fixture->nextLine(), '#2');
  }

  #[@test, @values(array("Test\n", "Test\r\n", "Test\r"))]
  public function has_one_line($value) {
    $this->assertTrue($this->newFixture($value)->hasMoreLines());
  }

  #[@test, @values(array("Test\n", "Test\r\n", "Test\r"))]
  public function read_one_line($value) {
    $this->assertEquals(new Line('Test'), $this->newFixture($value)->nextLine());
  }

  #[@test]
  public function has_one_line_without_line_ending() {
    $this->assertTrue($this->newFixture('Test')->hasMoreLines());
  }

  #[@test]
  public function read_one_line_without_line_ending() {
    $this->assertEquals(new Line('Test'), $this->newFixture('Test')->nextLine());
  }

  #[@test, @values(array("Line 1\nLine 2\n", "Line 1\r\nLine 2\r\n", "Line 1\rLine 2\r"))]
  public function two_lines($value) {
    $fixture= $this->newFixture($value);
    $this->assertEquals(
      array(new Line('Line 1'), new Line('Line 2')),
      array($fixture->nextLine(), $fixture->nextLine())
    );
  }

  #[@test]
  public function has_no_more_lines_after_reading_one_line() {
    $fixture= $this->newFixture("Test\n");
    $fixture->nextLine();
    $this->assertFalse($fixture->hasMoreLines());
  }

  #[@test]
  public function has_more_lines_after_reading_first_of_two_lines() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    $this->assertTrue($fixture->hasMoreLines());
  }

  #[@test]
  public function has_no_more_lines_after_reading_two_lines() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    $fixture->nextLine();
    $this->assertFalse($fixture->hasMoreLines());
  }

  #[@test]
  public function reset_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->resetLine($fixture->nextLine());
    $this->assertEquals(
      array(new Line('Line 1'), new Line('Line 2')),
      array($fixture->nextLine(), $fixture->nextLine())
    );
  }
}