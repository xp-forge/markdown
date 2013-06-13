<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Line;

class LineTest extends \unittest\TestCase {

  #[@test, @values('', 'Hello')]
  public function length_equals_length_of_buffer_passed_to_constructor($buffer) {
    $this->assertEquals(strlen($buffer), create(new Line($buffer))->length());
  }

  #[@test, @values('', 'Hello')]
  public function position_initially_defaults_to_zero($buffer) {
    $this->assertEquals(0, create(new Line($buffer))->pos());
  }

  #[@test]
  public function position_passable_as_second_constructor_parameter() {
    $this->assertEquals(2, create(new Line('buffer', 2))->pos());
  }

  #[@test]
  public function forward_position() {
    $l= new Line('Buffer');
    $l->forward(2);
    $this->assertEquals(2, $l->pos());
  }

  #[@test]
  public function forward_position_consecutively() {
    $l= new Line('Buffer');
    $l->forward(2);
    $l->forward(1);
    $this->assertEquals(3, $l->pos());
  }

  #[@test]
  public function forward_position_default_by_one() {
    $l= new Line('Buffer');
    $l->forward();
    $this->assertEquals(1, $l->pos());
  }

  #[@test]
  public function forwarding_returns_new_position() {
    $l= new Line('Buffer');
    $this->assertEquals(2, $l->forward(2));
  }

  #[@test]
  public function forwarding_consecutively_returns_new_position() {
    $l= new Line('Buffer');
    $l->forward(2);
    $this->assertEquals(3, $l->forward(1));
  }

  #[@test, @values(array(0, 1, 2, 3))]
  public function chr_returns_character_at_current_position($pos) {
    $buffer= 'Test';
    $this->assertEquals($buffer{$pos}, create(new Line($buffer, $pos))->chr());
  }

  #[@test, @values(array('T', 'Te', 'Tes', 'Test'))]
  public function matches($str) {
    $this->assertTrue(create(new Line('Test'))->matches($str));
  }

  #[@test, @values(array('', 'e', 'es', 'does-not-occur', "\0"))]
  public function does_not_match($str) {
    $this->assertFalse(create(new Line('Test'))->matches($str));
  }

  #[@test]
  public function next_with_one_character() {
    $this->assertEquals(5, create(new Line('Hello!'))->next('!'));
  }

  #[@test]
  public function next_with_two_characters() {
    $this->assertEquals(7, create(new Line('[[Hello]]'))->next(']]'));
  }

  #[@test]
  public function next_with_not_ocurring_pattern() {
    $this->assertEquals(-1, create(new Line('Hello'))->next('!'));
  }

  #[@test]
  public function next_with_two_patterns() {
    $this->assertEquals(5, create(new Line('Hello.'))->next(array('!', '.')));
  }

  #[@test]
  public function next_with_two_patterns_with_two_characters() {
    $this->assertEquals(7, create(new Line('[[Hello]]'))->next(array('>>', ']]')));
  }

  #[@test]
  public function next_with_two_not_ocurring_patterns() {
    $this->assertEquals(-1, create(new Line('Hello.'))->next(array('!', ',')));
  }

  #[@test]
  public function until_a_single_character() {
    $this->assertEquals('Hello', create(new Line('Hello!'))->until('!'));
  }

  #[@test]
  public function until_a_list_of_characters() {
    $this->assertEquals('Hello', create(new Line('Hello!'))->until('.,;:!?'));
  }

  #[@test]
  public function until_a_single_character_that_does_not_occurr() {
    $this->assertEquals('Hello', create(new Line('Hello'))->until('!'));
  }

  #[@test]
  public function until_advances_pointer() {
    $l= new Line('Test.');
    $l->until('.');
    $this->assertEquals(4, $l->pos());
  }

  #[@test, @values(array(array('Hello', 1), array('Hello World', 2), array('Hello New World', 3))]
  public function until_used_as_tokenizer($input, $size) {
    $l= new Line($input);
    $tokens= array();
    for ($i= 0; $i < $size; $i++) {
      $tokens[]= $l->until(' ');
      $l->forward();
    }
    $this->assertEquals(explode(' ', $input, $size), $tokens);
  }

  #[@test, @values('*', '_', '`')]
  public function ending_with_a_single_character($character) {
    $this->assertEquals('Hello', create(new Line($character.'Hello'.$character))->ending($character));
  }

  #[@test, @values('**', '__', '``')]
  public function ending_with_two_characters($characters) {
    $this->assertEquals('Hello', create(new Line($characters.'Hello'.$characters))->ending($characters));
  }

  #[@test]
  public function ending_advances_pointer() {
    $l= new Line('*Test*');
    $l->ending('*');
    $this->assertEquals(6, $l->pos());
  }

  #[@test, @values(array(
  #  array('(Hello)', 'Hello', '()'),
  #  array('((Hello))', '(Hello)', '()'),
  #  array('((Hello) World)', '(Hello) World', '()'),
  #  array('(Hello (World))', 'Hello (World)', '()'),
  #  array('(Hello (New) (World))', 'Hello (New) (World)', '()'),
  #  array('(Hello ((New)) (World))', 'Hello ((New)) (World)', '()'),
  #  array('[Hello]', 'Hello', '[]'),
  #  array('<Hello>', 'Hello', '<>'),
  #  array('{Hello}', 'Hello', '{}'),
  #)]
  public function matching_square_braces($input, $expected, $braces) {
    $this->assertEquals($expected, create(new Line($input))->matching($braces));
  }

  #[@test]
  public function matching_advances_pointer() {
    $l= new Line('((Test))');
    $l->matching('()');
    $this->assertEquals(8, $l->pos());
  }
}