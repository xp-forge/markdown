<?php namespace net\daringfireball\markdown\unittest;

class StrikeThroughTest extends MarkdownTest {

  #[@test]
  public function strikethrough() {
    $this->assertTransformed('<p><del>Hello</del></p>', '~~Hello~~');
  }

  #[@test]
  public function first_word() {
    $this->assertTransformed('<p><del>Hello</del> World</p>', '~~Hello~~ World');
  }

  #[@test]
  public function second_word() {
    $this->assertTransformed('<p>Hello <del>World</del></p>', 'Hello ~~World~~');
  }

  #[@test]
  public function can_be_used_in_the_middle_of_a_word() {
    $this->assertTransformed('<p>Strike<del>f</del>through</p>', 'Strike~~f~~through');
  }

  #[@test, @values([
  #  'a~', 'b~~', 'c~~~',
  #  '~a', '~~b', '~~~c',
  #  '~not~'
  #])]
  public function not_strikethrough($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }
}