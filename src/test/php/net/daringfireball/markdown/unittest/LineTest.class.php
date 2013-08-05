<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Line;

class LineTest extends \unittest\TestCase {

  #[@test, @values(array('', 'Hello'))]
  public function length_equals_length_of_buffer_passed_to_constructor($buffer) {
    $this->assertEquals(strlen($buffer), create(new Line($buffer))->length());
  }

  #[@test, @values(array('', 'Hello'))]
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

  #[@test, @values(array('*', '_', '`'))]
  public function ending_with_a_single_character($character) {
    $this->assertEquals('Hello', create(new Line($character.'Hello'.$character))->ending($character));
  }

  #[@test, @values(array('**', '__', '``'))]
  public function ending_with_two_characters($characters) {
    $this->assertEquals('Hello', create(new Line($characters.'Hello'.$characters))->ending($characters));
  }

  #[@test, @values(array('`` $code; ``', array('`` $code;``'))]
  public function ending_with_any_of($input) {
    $this->assertEquals('$code;', create(new Line($input))->ending(array(' ``', '``'), 3));
  }

  #[@test]
  public function ending_advances_pointer() {
    $l= new Line('*Test*');
    $l->ending('*');
    $this->assertEquals(6, $l->pos());
  }

  #[@test]
  public function ending_regards_double_delimiter_nested() {
    $this->assertEquals('Hello **World**', create(new Line('*Hello **World***'))->ending('*'));
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

  #[@test]
  public function slice_of_given_length() {
    $this->assertEquals('Hello', create(new Line('Hello'))->slice(5));
  }

  #[@test]
  public function slice_of_given_length_with_left_offset() {
    $this->assertEquals('ello', create(new Line('Hello'))->slice(5, 1, 0));
  }

  #[@test]
  public function slice_of_given_length_with_right_offset() {
    $this->assertEquals('Hell', create(new Line('Hello'))->slice(5, 0, -1));
  }

  #[@test]
  public function slice_of_given_length_with_left_and_right_offset() {
    $this->assertEquals('ell', create(new Line('Hello'))->slice(5, 1, -1));
  }

  #[@test]
  public function slice_advances_pointer() {
    $l= new Line('Test');
    $l->slice(2);
    $this->assertEquals(2, $l->pos());
  }

  #[@test]
  public function left_offset_does_not_affect_how_far_slice_advances_pointer() {
    $l= new Line('Test');
    $l->slice(3, 1, 0);
    $this->assertEquals(3, $l->pos());
  }

  #[@test]
  public function right_offset_does_not_affect_how_far_slice_advances_pointer() {
    $l= new Line('Test');
    $l->slice(3, 0, -1);
    $this->assertEquals(3, $l->pos());
  }

  #[@test]
  public function slice() {
    $l= new Line('He said: "Hello"!');
    $l->forward(strlen('He said: '));
    $this->assertEquals('Hello', $l->slice(7, 1, -1));
    $this->assertEquals('!', $l->until("\n"));
  }

  #[@test, @values(array('This `` $files= []; `` is an initialization', 'This `` $files= [];`` is an initialization'))]
  public function code_with_spaces($input) {
    $line= new Line($input);
    $before= $line->until('`');
    $code= $line->ending(array(' ``', '``'), strlen('`` '));
    $this->assertEquals(
      array('before' => 'This ', 'code' => '$files= [];', 'after' => ' is an initialization'),
      array('before' => $before, 'code' => $code, 'after' => $line->until("\n"))
    );
  }

  #[@test, @expect(class= 'lang.IllegalStateException', withMessage= 'Unmatched *')]
  public function ending_single_delimiter_not_found() {
    create(new Line('*Hello'))->ending('*');
  }

  #[@test, @expect(class= 'lang.IllegalStateException', withMessage= 'Unmatched **')]
  public function ending_double_delimiter_not_found() {
    create(new Line('**Hello'))->ending('**');
  }

  #[@test, @expect(class= 'lang.IllegalStateException', withMessage= 'Unmatched **, *')]
  public function none_of_ending_delimiters_not_found() {
    create(new Line('*Hello'))->ending(array('**', '*'));
  }

  #[@test]
  public function replace() {
    $l= new Line('Test');
    $l->replace('/[a-z]/', '.');
    $this->assertEquals('T...', (string)$l);
  }

  #[@test]
  public function replace_to_shorter_string_changes_length() {
    $l= new Line('Test');
    $l->replace('/[a-z]/', '');
    $this->assertEquals(1, $l->length());
  }

  #[@test]
  public function replace_to_longer_string_changes_length() {
    $l= new Line('Test');
    $l->replace('/[a-z]/', '$0$0');
    $this->assertEquals(7, $l->length());
  }

  #[@test]
  public function replace_illegal_pattern() {
    $l= new Line('Test');
    try {
      $l->replace('/(/', '.');
      $this->fail('No exception raised', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) {
      // OK
    }
    $this->assertEquals('Test', (string)$l);
  }
}