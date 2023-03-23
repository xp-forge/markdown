<?php namespace net\daringfireball\markdown\unittest;

use test\Assert;
use test\{Test, Values};

class StrikeThroughTest extends MarkdownTest {

  #[Test]
  public function strikethrough() {
    $this->assertTransformed('<p><del>Hello</del></p>', '~~Hello~~');
  }

  #[Test]
  public function first_word() {
    $this->assertTransformed('<p><del>Hello</del> World</p>', '~~Hello~~ World');
  }

  #[Test]
  public function second_word() {
    $this->assertTransformed('<p>Hello <del>World</del></p>', 'Hello ~~World~~');
  }

  #[Test]
  public function can_be_used_in_the_middle_of_a_word() {
    $this->assertTransformed('<p>Strike<del>f</del>through</p>', 'Strike~~f~~through');
  }

  #[Test, Values(['a~', 'b~~', 'c~~~', '~a', '~~b', '~not~'])]
  public function not_strikethrough($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }
}