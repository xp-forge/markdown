<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Line;
use test\Assert;
use test\{Test, Values};

/**
 * Base class for Input implementation tests.
 */
abstract class InputTest {

  /**
   * Create and return new fixture.
   *
   * @param   string source
   * @return  net.daringfireball.markdown.Input
   */
  protected abstract function newFixture($source);

  #[Test]
  public function has_empty_input() {
    Assert::false($this->newFixture('')->hasMoreLines());
  }

  #[Test]
  public function has_empty_input_twice() {
    $fixture= $this->newFixture('');
    Assert::false($fixture->hasMoreLines(), '#1');
    Assert::false($fixture->hasMoreLines(), '#2');
  }

  #[Test]
  public function read_empty_input() {
    Assert::null($this->newFixture('')->nextLine());
  }

  #[Test]
  public function read_empty_input_twice() {
    $fixture= $this->newFixture('');
    Assert::null($fixture->nextLine(), '#1');
    Assert::null($fixture->nextLine(), '#2');
  }

  #[Test, Values(["Test\n", "Test\r\n", "Test\r"])]
  public function has_one_line($value) {
    Assert::true($this->newFixture($value)->hasMoreLines());
  }

  #[Test, Values(["Test\n", "Test\r\n", "Test\r"])]
  public function read_one_line($value) {
    Assert::equals(new Line('Test'), $this->newFixture($value)->nextLine());
  }

  #[Test]
  public function has_one_line_without_line_ending() {
    Assert::true($this->newFixture('Test')->hasMoreLines());
  }

  #[Test]
  public function read_one_line_without_line_ending() {
    Assert::equals(new Line('Test'), $this->newFixture('Test')->nextLine());
  }

  #[Test, Values(["Line 1\nLine 2\n", "Line 1\r\nLine 2\r\n", "Line 1\rLine 2\r"])]
  public function two_lines($value) {
    $fixture= $this->newFixture($value);
    Assert::equals(
      [new Line('Line 1'), new Line('Line 2')],
      [$fixture->nextLine(), $fixture->nextLine()]
    );
  }

  #[Test, Values(["1\n2\n", "1\r\n2\r\n", "1\r2\r"])]
  public function two_lines_with_single_characters($value) {
    $fixture= $this->newFixture($value);
    Assert::equals(
      [new Line('1'), new Line('2')],
      [$fixture->nextLine(), $fixture->nextLine()]
    );
  }

  #[Test]
  public function has_no_more_lines_after_reading_one_line() {
    $fixture= $this->newFixture("Test\n");
    $fixture->nextLine();
    Assert::false($fixture->hasMoreLines());
  }

  #[Test]
  public function has_more_lines_after_reading_first_of_two_lines() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    Assert::true($fixture->hasMoreLines());
  }

  #[Test]
  public function has_no_more_lines_after_reading_two_lines() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    $fixture->nextLine();
    Assert::false($fixture->hasMoreLines());
  }

  #[Test]
  public function reset_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->resetLine($fixture->nextLine());
    Assert::equals(
      [new Line('Line 1'), new Line('Line 2')],
      [$fixture->nextLine(), $fixture->nextLine()]
    );
  }

  #[Test]
  public function current_line_at_beginning() {
    $fixture= $this->newFixture('');
    Assert::equals(1, $fixture->currentLine());
  }

  #[Test]
  public function current_line_after_next_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->nextLine();
    Assert::equals(2, $fixture->currentLine());
  }

  #[Test]
  public function current_line_after_resetting_a_line() {
    $fixture= $this->newFixture("Line 1\nLine 2\n");
    $fixture->resetLine($fixture->nextLine());
    Assert::equals(1, $fixture->currentLine());
  }

  #[Test]
  public function reset_line_with_null_ignroed() {
    $fixture= $this->newFixture('Input');
    $fixture->resetLine(null);
    $fixture->nextLine();
    Assert::false($fixture->hasMoreLines());
  }

  #[Test]
  public function string_representation() {
    $string= $this->newFixture('')->toString();
    Assert::equals(
      1,
      preg_match('/^.+Input\(line 1 of .+/m', $string),
      $string
    );
  }
}