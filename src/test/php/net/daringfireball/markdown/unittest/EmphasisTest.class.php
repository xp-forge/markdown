<?php namespace net\daringfireball\markdown\unittest;

class EmphasisTest extends MarkdownTest {

  #[@test, @values(['*Hello*', '_Hello_'])]
  public function emphasis($input) {
    $this->assertTransformed('<p><em>Hello</em></p>', $input);
  }

  #[@test, @values(['*Hello* World', '_Hello_ World'])]
  public function emphasized_first_word($input) {
    $this->assertTransformed('<p><em>Hello</em> World</p>', $input);
  }

  #[@test, @values(['Hello *World*', 'Hello _World_'])]
  public function emphasized_second_word($input) {
    $this->assertTransformed('<p>Hello <em>World</em></p>', $input);
  }

  #[@test, @values(['*Hello* *World*', '_Hello_ _World_'])]
  public function two_emphasized_words($input) {
    $this->assertTransformed('<p><em>Hello</em> <em>World</em></p>', $input);
  }

  #[@test, @values([
  #  'Use one marker for *emphasizing words*.',
  #  'Use one marker for _emphasizing words_.',
  #])]
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

  #[@test, @values(['**Hello**', '__Hello__'])]
  public function strong($input) {
    $this->assertTransformed('<p><strong>Hello</strong></p>', $input);
  }

  #[@test, @values(['**Hello** World', '__Hello__ World'])]
  public function strong_first_word($input) {
    $this->assertTransformed('<p><strong>Hello</strong> World</p>', $input);
  }

  #[@test, @values(['Hello **World**', 'Hello __World__'])]
  public function strong_second_word($input) {
    $this->assertTransformed('<p>Hello <strong>World</strong></p>', $input);
  }

  #[@test, @values([
  #  'Use two markers for **strong emphasis**.',
  #  'Use two markers for __strong emphasis__.',
  #])]
  public function strong_for_multiple_words($input) {
    $this->assertTransformed('<p>Use two markers for <strong>strong emphasis</strong>.</p>', $input);
  }

  #[@test, @values(['*Hello **World**!*', '_Hello __World__!_'])]
  public function strong_inside_emphasis($input) {
    $this->assertTransformed('<p><em>Hello <strong>World</strong>!</em></p>', $input);
  }

  #[@test, @values(['**Hello *World*!**', '__Hello _World_!__'])]
  public function emphasis_inside_strong($input) {
    $this->assertTransformed('<p><strong>Hello <em>World</em>!</strong></p>', $input);
  }

  #[@test, @values(['***Test***', '___Test___'])]
  public function triple($input) {
    $this->assertTransformed('<p><strong><em>Test</em></strong></p>', $input);
  }

  #[@test]
  public function two_emphasized_words_inside_strong() {
    $this->assertTransformed(
      '<p><strong>&quot;<em>Hello</em>&quot;, he said. &quot;How are <em>you</em>?&quot;</strong></p>',
      '**"*Hello*", he said. "How are *you*?"**'
    );
  }

  #[@test]
  public function mathematic_formula_is_not_emphasized() {
    $this->assertTransformed('<p>a * b * c</p>', 'a * b * c');
  }

  #[@test, @values(['a*', 'b**', 'c***'])]
  public function reference_at_end($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }

  #[@test]
  public function underscore_inside_word_is_not_emphasized() {
    $this->assertTransformed('<p>The hello_world function</p>', 'The hello_world function');
  }
}