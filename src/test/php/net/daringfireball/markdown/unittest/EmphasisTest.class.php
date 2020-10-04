<?php namespace net\daringfireball\markdown\unittest;

use unittest\{Test, Values};

class EmphasisTest extends MarkdownTest {

  #[Test, Values(['*Hello*', '_Hello_'])]
  public function emphasis($input) {
    $this->assertTransformed('<p><em>Hello</em></p>', $input);
  }

  #[Test, Values(['*Hello* World', '_Hello_ World'])]
  public function emphasized_first_word($input) {
    $this->assertTransformed('<p><em>Hello</em> World</p>', $input);
  }

  #[Test, Values(['Hello *World*', 'Hello _World_'])]
  public function emphasized_second_word($input) {
    $this->assertTransformed('<p>Hello <em>World</em></p>', $input);
  }

  #[Test, Values(['*Hello* *World*', '_Hello_ _World_'])]
  public function two_emphasized_words($input) {
    $this->assertTransformed('<p><em>Hello</em> <em>World</em></p>', $input);
  }

  #[Test, Values(['Use one marker for *emphasizing words*.', 'Use one marker for _emphasizing words_.',])]
  public function emphasis_for_multiple_words($input) {
    $this->assertTransformed('<p>Use one marker for <em>emphasizing words</em>.</p>', $input);
  }

  #[Test]
  public function emphasis_can_be_used_in_the_middle_of_a_word() {
    $this->assertTransformed('<p>un<em>frigging</em>believable</p>', 'un*frigging*believable');
  }

  #[Test]
  public function literal_asterisks() {
    $this->assertTransformed('<p>*literal asterisks*</p>', '\*literal asterisks\*');
  }

  #[Test, Values(['**Hello**', '__Hello__'])]
  public function strong($input) {
    $this->assertTransformed('<p><strong>Hello</strong></p>', $input);
  }

  #[Test, Values(['**Hello** World', '__Hello__ World'])]
  public function strong_first_word($input) {
    $this->assertTransformed('<p><strong>Hello</strong> World</p>', $input);
  }

  #[Test, Values(['Hello **World**', 'Hello __World__'])]
  public function strong_second_word($input) {
    $this->assertTransformed('<p>Hello <strong>World</strong></p>', $input);
  }

  #[Test, Values(['Use two markers for **strong emphasis**.', 'Use two markers for __strong emphasis__.',])]
  public function strong_for_multiple_words($input) {
    $this->assertTransformed('<p>Use two markers for <strong>strong emphasis</strong>.</p>', $input);
  }

  #[Test, Values(['*Hello **World**!*', '_Hello __World__!_'])]
  public function strong_inside_emphasis($input) {
    $this->assertTransformed('<p><em>Hello <strong>World</strong>!</em></p>', $input);
  }

  #[Test, Values(['**Hello *World*!**', '__Hello _World_!__'])]
  public function emphasis_inside_strong($input) {
    $this->assertTransformed('<p><strong>Hello <em>World</em>!</strong></p>', $input);
  }

  #[Test, Values(['***Test***', '___Test___'])]
  public function triple($input) {
    $this->assertTransformed('<p><strong><em>Test</em></strong></p>', $input);
  }

  #[Test]
  public function two_emphasized_words_inside_strong() {
    $this->assertTransformed(
      '<p><strong>&quot;<em>Hello</em>&quot;, he said. &quot;How are <em>you</em>?&quot;</strong></p>',
      '**"*Hello*", he said. "How are *you*?"**'
    );
  }

  #[Test]
  public function mathematic_formula_is_not_emphasized() {
    $this->assertTransformed('<p>a * b * c</p>', 'a * b * c');
  }

  #[Test, Values(['a*', 'b**', 'c***'])]
  public function reference_at_end($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }

  #[Test]
  public function underscore_inside_word_is_not_emphasized() {
    $this->assertTransformed('<p>The hello_world function</p>', 'The hello_world function');
  }

  #[Test]
  public function italics_and_bold_formatting_combined() {
    $this->assertTransformed(
      '<p>You can also combine <em>italics and <strong>bold</strong> formatting</em>, e.g.:</p>',
      'You can also combine *italics and **bold** formatting*, e.g.:'
    );
  }
}