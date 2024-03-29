<?php namespace net\daringfireball\markdown\unittest;

use lang\IllegalStateException;
use net\daringfireball\markdown\Line;
use test\{Assert, Expect, Test, TestCase, Values};

class LineTest {

  #[Test, Values(['', 'Hello'])]
  public function length_equals_length_of_buffer_passed_to_constructor($buffer) {
    Assert::equals(strlen($buffer), (new Line($buffer))->length());
  }

  #[Test, Values(['', 'Hello'])]
  public function position_initially_defaults_to_zero($buffer) {
    Assert::equals(0, (new Line($buffer))->pos());
  }

  #[Test]
  public function position_passable_as_second_constructor_parameter() {
    Assert::equals(2, (new Line('buffer', 2))->pos());
  }

  #[Test]
  public function forward_position() {
    $l= new Line('Buffer');
    $l->forward(2);
    Assert::equals(2, $l->pos());
  }

  #[Test]
  public function forward_position_consecutively() {
    $l= new Line('Buffer');
    $l->forward(2);
    $l->forward(1);
    Assert::equals(3, $l->pos());
  }

  #[Test]
  public function forward_position_default_by_one() {
    $l= new Line('Buffer');
    $l->forward();
    Assert::equals(1, $l->pos());
  }

  #[Test]
  public function forwarding_returns_new_position() {
    $l= new Line('Buffer');
    Assert::equals(2, $l->forward(2));
  }

  #[Test]
  public function forwarding_consecutively_returns_new_position() {
    $l= new Line('Buffer');
    $l->forward(2);
    Assert::equals(3, $l->forward(1));
  }

  #[Test, Values([0, 1, 2, 3])]
  public function chr_returns_character_at_current_position($pos) {
    $buffer= 'Test';
    Assert::equals($buffer[$pos], (new Line($buffer, $pos))->chr());
  }

  #[Test]
  public function chr_with_offset() {
    Assert::equals('T', (new Line('Test', 1))->chr(-1));
  }

  #[Test, Values([-1, -2, -10000])]
  public function chr_with_offset_before_beginning($offset) {
    Assert::null((new Line('Test', 0))->chr($offset));
  }

  #[Test, Values([4, 5, 10000])]
  public function chr_with_offset_after_end($offset) {
    Assert::null((new Line('Test', 0))->chr($offset));
  }

  #[Test, Values(['T', 'Te', 'Tes', 'Test'])]
  public function matches($str) {
    Assert::true((new Line('Test'))->matches($str));
  }

  #[Test, Values(['', 'e', 'es', 'does-not-occur', "\0"])]
  public function does_not_match($str) {
    Assert::false((new Line('Test'))->matches($str));
  }

  #[Test]
  public function next_with_one_character() {
    Assert::equals(5, (new Line('Hello!'))->next('!'));
  }

  #[Test]
  public function next_with_two_characters() {
    Assert::equals(7, (new Line('[[Hello]]'))->next(']]'));
  }

  #[Test]
  public function next_with_not_ocurring_pattern() {
    Assert::equals(-1, (new Line('Hello'))->next('!'));
  }

  #[Test]
  public function next_with_two_patterns() {
    Assert::equals(5, (new Line('Hello.'))->next(['!', '.']));
  }

  #[Test]
  public function next_with_two_patterns_with_two_characters() {
    Assert::equals(7, (new Line('[[Hello]]'))->next(['>>', ']]']));
  }

  #[Test]
  public function next_with_two_not_ocurring_patterns() {
    Assert::equals(-1, (new Line('Hello.'))->next(['!', ',']));
  }

  #[Test]
  public function until_a_single_character() {
    Assert::equals('Hello', (new Line('Hello!'))->until('!'));
  }

  #[Test]
  public function until_a_list_of_characters() {
    Assert::equals('Hello', (new Line('Hello!'))->until('.,;:!?'));
  }

  #[Test]
  public function until_a_single_character_that_does_not_occurr() {
    Assert::equals('Hello', (new Line('Hello'))->until('!'));
  }

  #[Test]
  public function until_advances_pointer() {
    $l= new Line('Test.');
    $l->until('.');
    Assert::equals(4, $l->pos());
  }

  #[Test, Values([['Hello', 1], ['Hello World', 2], ['Hello New World', 3]])]
  public function until_used_as_tokenizer($input, $size) {
    $l= new Line($input);
    $tokens= [];
    for ($i= 0; $i < $size; $i++) {
      $tokens[]= $l->until(' ');
      $l->forward();
    }
    Assert::equals(explode(' ', $input, $size), $tokens);
  }

  #[Test, Values(['*', '_', '`'])]
  public function ending_with_a_single_character($character) {
    Assert::equals('Hello', (new Line($character.'Hello'.$character))->ending($character));
  }

  #[Test, Values(['**', '__', '``'])]
  public function ending_with_two_characters($characters) {
    Assert::equals('Hello', (new Line($characters.'Hello'.$characters))->ending($characters));
  }

  #[Test, Values(['`` $code; ``', '`` $code;``'])]
  public function ending_with_any_of($input) {
    Assert::equals('$code;', (new Line($input))->ending([' ``', '``'], 3));
  }

  #[Test]
  public function ending_advances_pointer() {
    $l= new Line('*Test*');
    $l->ending('*');
    Assert::equals(6, $l->pos());
  }

  #[Test, Values(['*Hello **World***', 'Say *Hello **World***', 'He said *Hello **World***', '*Hello **World*** is a common phrase'])]
  public function ending_regards_double_delimiter_nested($line) {
    $line= new Line($line);
    $line->until('*');
    Assert::equals('Hello **World**', $line->ending('*'));
  }

  #[Test, Values([['(Hello)', 'Hello', '()'], ['[Hello]', 'Hello', '[]'], ['<Hello>', 'Hello', '<>'], ['{Hello}', 'Hello', '{}'], ['((Hello))', '(Hello)', '()'], ['((Hello) World)', '(Hello) World', '()'], ['(Hello (World))', 'Hello (World)', '()'], ['(Hello (New) (World))', 'Hello (New) (World)', '()'], ['(Hello ((New)) (World))', 'Hello ((New)) (World)', '()']])]
  public function matching_square_braces($input, $expected, $braces) {
    Assert::equals($expected, (new Line($input))->matching($braces));
  }

  #[Test]
  public function matching_advances_pointer() {
    $l= new Line('((Test))');
    $l->matching('()');
    Assert::equals(8, $l->pos());
  }

  #[Test]
  public function slice_of_given_length() {
    Assert::equals('Hello', (new Line('Hello'))->slice(5));
  }

  #[Test]
  public function slice_of_given_length_with_left_offset() {
    Assert::equals('ello', (new Line('Hello'))->slice(5, 1, 0));
  }

  #[Test]
  public function slice_of_given_length_with_right_offset() {
    Assert::equals('Hell', (new Line('Hello'))->slice(5, 0, -1));
  }

  #[Test]
  public function slice_of_given_length_with_left_and_right_offset() {
    Assert::equals('ell', (new Line('Hello'))->slice(5, 1, -1));
  }

  #[Test]
  public function slice_advances_pointer() {
    $l= new Line('Test');
    $l->slice(2);
    Assert::equals(2, $l->pos());
  }

  #[Test]
  public function left_offset_does_not_affect_how_far_slice_advances_pointer() {
    $l= new Line('Test');
    $l->slice(3, 1, 0);
    Assert::equals(3, $l->pos());
  }

  #[Test]
  public function right_offset_does_not_affect_how_far_slice_advances_pointer() {
    $l= new Line('Test');
    $l->slice(3, 0, -1);
    Assert::equals(3, $l->pos());
  }

  #[Test]
  public function slice() {
    $l= new Line('He said: "Hello"!');
    $l->forward(strlen('He said: '));
    Assert::equals('Hello', $l->slice(7, 1, -1));
    Assert::equals('!', $l->until("\n"));
  }

  #[Test, Values(['This `` $files= []; `` is an initialization', 'This `` $files= [];`` is an initialization'])]
  public function code_with_spaces($input) {
    $line= new Line($input);
    $before= $line->until('`');
    $code= $line->ending([' ``', '``'], strlen('`` '));
    Assert::equals(
      ['before' => 'This ', 'code' => '$files= [];', 'after' => ' is an initialization'],
      ['before' => $before, 'code' => $code, 'after' => $line->until("\n")]
    );
  }

  #[Test, Expect(class: IllegalStateException::class, message: 'Unmatched *')]
  public function ending_single_delimiter_not_found() {
    (new Line('*Hello'))->ending('*');
  }

  #[Test, Expect(class: IllegalStateException::class, message: 'Unmatched **')]
  public function ending_double_delimiter_not_found() {
    (new Line('**Hello'))->ending('**');
  }

  #[Test, Expect(class: IllegalStateException::class, message: 'Unmatched **, *')]
  public function none_of_ending_delimiters_not_found() {
    (new Line('*Hello'))->ending(['**', '*']);
  }

  #[Test]
  public function replace() {
    $l= new Line('Test');
    $l->replace('/[a-z]/', '.');
    Assert::equals('T...', (string)$l);
  }

  #[Test]
  public function replace_to_shorter_string_changes_length() {
    $l= new Line('Test');
    $l->replace('/[a-z]/', '');
    Assert::equals(1, $l->length());
  }

  #[Test]
  public function replace_to_longer_string_changes_length() {
    $l= new Line('Test');
    $l->replace('/[a-z]/', '$0$0');
    Assert::equals(7, $l->length());
  }

  #[Test]
  public function replace_illegal_pattern() {
    $l= new Line('Test');
    try {
      $l->replace('/(/', '.');
      $this->fail('No exception raised', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) {
      // OK
    }
    Assert::equals('Test', (string)$l);
  }

  #[Test]
  public function read_string_offset() {
    $l= new Line('Test');
    Assert::equals('T', $l[0]);
  }

  #[Test]
  public function test_string_offset() {
    $l= new Line('Test');
    Assert::true(isset($l[0]));
  }

  #[Test]
  public function read_non_existant_string_offset() {
    $l= new Line('');
    Assert::null($l[0]);
  }

  #[Test]
  public function test_non_existant_string_offset() {
    $l= new Line('');
    Assert::false(isset($l[0]));
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'net.daringfireball.markdown.Line("Hello" @ 2)',
      (new Line('Hello', 2))->toString()
    );
  }
}