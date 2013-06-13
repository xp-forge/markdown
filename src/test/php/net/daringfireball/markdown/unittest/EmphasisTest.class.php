<?php namespace net\daringfireball\markdown\unittest;

class EmphasisTest extends MarkdownTest {

  #[@test, @values(array('*Hello*', '_Hello_'))]
  public function emphasis($input) {
    $this->assertTransformed('<p><em>Hello</em></p>', $input);
  }

  #[@test, @values(array('*Hello* World', '_Hello_ World'))]
  public function emphasized_first_word($input) {
    $this->assertTransformed('<p><em>Hello</em> World</p>', $input);
  }

  #[@test, @values(array('Hello *World*', 'Hello _World_'))]
  public function emphasized_second_word($input) {
    $this->assertTransformed('<p>Hello <em>World</em></p>', $input);
  }

  #[@test, @values(array(
  #  'Use one marker for *emphasizing words*.',
  #  'Use one marker for _emphasizing words_.',
  #)]
  public function emphasis_for_multiple_words($input) {
    $this->assertTransformed('<p>Use one marker for <em>emphasizing words</em>.</p>', $input);
  }

  #[@test]
  public function emphasis_can_be_used_in_the_middle_of_a_word() {
    $this->assertTransformed('<p>un<em>frigging</em>believable</p>', 'un*frigging*believable');
  }

  #[@test]
  public function literal_asterisks() {
    $this->assertTransformed('<p>*literal asterisks*</p>', '\*literal asterisks\*');
  }

  #[@test, @values(array('**Hello**', '__Hello__'))]
  public function strong($input) {
    $this->assertTransformed('<p><strong>Hello</strong></p>', $input);
  }

  #[@test, @values(array('**Hello** World', '__Hello__ World'))]
  public function strong_first_word($input) {
    $this->assertTransformed('<p><strong>Hello</strong> World</p>', $input);
  }

  #[@test, @values(array('Hello **World**', 'Hello __World__'))]
  public function strong_second_word($input) {
    $this->assertTransformed('<p>Hello <strong>World</strong></p>', $input);
  }

  #[@test, @values(array(
  #  'Use two markers for **strong emphasis**.',
  #  'Use two markers for __strong emphasis__.',
  #)]
  public function strong_for_multiple_words($input) {
    $this->assertTransformed('<p>Use two markers for <strong>strong emphasis</strong>.</p>', $input);
  }
}