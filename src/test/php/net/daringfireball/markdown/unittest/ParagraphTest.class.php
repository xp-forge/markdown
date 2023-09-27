<?php namespace net\daringfireball\markdown\unittest;

use test\Assert;
use test\{Test, Values};

class ParagraphTest extends MarkdownTest {

  #[Test]
  public function one_line() {
    $this->assertTransformed('<p>Hello</p>', 'Hello');
  }

  #[Test]
  public function two_consecutive_lines_are_joined_in_one_paragraph() {
    $this->assertTransformed("<p>Hello\nWorld</p>", "Hello\nWorld");
  }

  #[Test]
  public function an_empty_line_opens_a_new_paragraph() {
    $this->assertTransformed('<p>Hello</p><p>World</p>', "Hello\n\nWorld");
  }

  #[Test, Values(["\n\n\n", "\n\n\n\n"])]
  public function consecutive_empty_lines_open_a_new_paragraph($nl) {
    $this->assertTransformed('<p>Hello</p><p>World</p>', 'Hello'.$nl.'World');
  }

  #[Test, Values(['  ', '   '])]
  public function manual_line_break_with_two_or_more_spaces_after_text($sp) {
    $this->assertTransformed('<p>Hello<br></p>', 'Hello'.$sp);
  }

  #[Test, Values(['  ', '   '])]
  public function manual_line_break_with_two_or_more_spaces($sp) {
    $this->assertTransformed('<p><br></p>', $sp);
  }
}