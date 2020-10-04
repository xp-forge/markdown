<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Line;
use unittest\{Test, Values};

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

  #[Test]
  public function has_empty_input() {
    $this->assertFalse($this->newFixture('')->hasMoreLines());
  }

  #[Test]
  public function has_empty_input_twice() {
    $fixture= $this->newFixture('');
    $this->assertFalse($fixture->hasMoreLines(), '#1');
    $this->assertFalse($fixture->hasMoreLines(), '#2');
  }

  #[Test]
  public function read_empty_input() {
    $this->assertNull($this->newFixture('')->nextLine());
  }

  #[Test]
  public function read_empty_input_twice() {
    $fixture= $this->newFixture('');
    $this->assertNull($fixture->nextLine(), '#1');
    $this->assertNull($fixture->nextLine(), '#2');
  }

  #[Test, Values(["Test\n", "Test\r\n", "Test\r"])]
  public function has_one_line($value) {
    $this->assertTrue($this->newFixture($value)->hasMoreLines());
  }

  #[Test, Values(["Test\n", "Test\r\n", "Test\r"])]
  public function read_one_line($value) {
    $this->assertEquals(new Line('Test'), $this->newFixture($value)->nextLine());
  }

  #[Test]
  public function has_one_line_without_line_ending() {
    $this->assertTrue($this->newFixture('Test')->hasMoreLines());
  }

  #[Test]
  public function read_one_line_without_line_ending() {
    $this->assertEquals(new Line('Test'), $this->newFixture('Test')->nextLine());
  }

  #[Test, Values(["Line 1\nLine 2\n", "Line 1\r\nLine 2\r\n", "Line 1\rLine 2\r"])]
  public function two_lines($value) {
    $fixture= $this->newFixture($value);
    $this->assertEquals(
      [new Line('Line 1'), new Line('Line 2')],
      [$fixture->nextLine(), $fixture->nextLine()]
    );
  }

  #[Test, Values(["1\n2\n", "1\r\n2\r\n", "1\r2\r"])]
  public function two_lines_with_single_characters($value) {
    $fixture= $this->newFixture($value);
    $this->assertEquals(
      [new Line('1'), new Line('2')],
      [$fixture->nextLine(), $fixture->nextLine()]
    );
  }

  #[Test]
  public function has_no_more_lines_after_reading_one_line() {
    $fixture= $this->newFixture("Test\n");
    $fixture->nextLine();
    $this->assertFalse($fixture->hasMoreLines());
  }

  #[Test]
  public function has_more_lines_after_reading_first_of_two_lines() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    $this->assertTrue($fixture->hasMoreLines());
  }

  #[Test]
  public function has_no_more_lines_after_reading_two_lines() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    $fixture->nextLine();
    $this->assertFalse($fixture->hasMoreLines());
  }

  #[Test]
  public function reset_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->resetLine($fixture->nextLine());
    $this->assertEquals(
      [new Line('Line 1'), new Line('Line 2')],
      [$fixture->nextLine(), $fixture->nextLine()]
    );
  }

  #[Test]
  public function current_line_at_beginning() {
    $fixture= $this->newFixture('');
    $this->assertEquals(1, $fixture->currentLine());
  }

  #[Test]
  public function current_line_after_next_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    $this->assertEquals(2, $fixture->currentLine());
  }

  #[Test]
  public function current_line_after_resetting_a_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->resetLine($fixture->nextLine());
    $this->assertEquals(1, $fixture->currentLine());
  }

  #[Test]
  public function reset_line_with_null_ignroed() {
    $fixture= $this->newFixture('Input');
    $fixture->resetLine(null);
    $fixture->nextLine();
    $this->assertFalse($fixture->hasMoreLines());
  }

  #[Test]
  public function string_representation() {
    $string= $this->newFixture('')->toString();
    $this->assertEquals(
      1,
      preg_match('/^.+Input\(line 1 of .+/m', $string),
      $string
    );
  }
}