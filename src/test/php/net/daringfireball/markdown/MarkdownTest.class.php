<?php namespace net\daringfireball\markdown;

class MarkdownTest extends \unittest\TestCase {
  protected $fixture;

  /**
   * Create fixture
   */
  public function setUp() {
    $this->fixture= new Markdown();
  }

  /**
   * Assertion helper
   *
   * @param  string $expected
   * @param  string $input
   * @throws unittest.AssertionFailedError
   */
  protected function assertTransformed($expected, $input) {
    $this->assertEquals($expected, $this->fixture->transform($input));
  }

  #[@test, @values(array('', ' ', 'Hello World'))]
  public function transforming_plain_text_equals_itself($value) {
    $this->assertTransformed('<p>'.$value.'</p>', $value);
  }

  #[@test, @values(array('<', '>', '&', '"', "'"))]
  public function special_characters_are_escaped($value) {
    $this->assertTransformed('<p>'.htmlspecialchars($value).'</p>', $value);
  }

  #[@test]
  public function escaping() {
    $this->assertTransformed('<p>4 &lt; 5</p>', '4 < 5');
  }

  #[@test, @values(array('AT&amp;T', '&quot;'))]
  public function htmlentities_are_left_untouched($value) {
    $this->assertTransformed('<p>'.$value.'</p>', $value);
  }

  #[@test, @values(array("* One\n* Two\n* Three", "- One\n- Two\n- Three", "+ One\n+ Two\n+ Three"))]
  public function unordered_list($value) {
    $this->assertTransformed(
      '<ul><li>One</li><li>Two</li><li>Three</li></ul>',
      $value
    );
  }

  #[@test]
  public function ordered_list() {
    $this->assertTransformed(
      '<ol><li>One</li><li>Two</li><li>Three</li></ol>',
      "1. One\n2. Two\n3. Three"
    );
  }

  #[@test]
  public function actual_numbers_used_have_no_effect_on_ordered_list() {
    $this->assertTransformed(
      '<ol><li>One</li><li>Two</li><li>Three</li></ol>',
      "1. One\n1. Two\n1. Three"
    );
  }

  #[@test]
  public function ordered_list_triggered_by_accident() {
    $this->assertTransformed(
      '<ol><li>What a great season.</li></ol>',
      '1986. What a great season.'
    );
  }

  #[@test]
  public function backslash_escaping_for_above_accident() {
    $this->assertTransformed(
      '<p>1986. What a great season.</p>',
      '1986\. What a great season.'
    );
  }

  #[@test]
  public function code() {
    $this->assertTransformed(
      '<p>Use the <code>printf()</code> function</p>',
      'Use the `printf()` function'
    );
  }

  #[@test]
  public function code_is_escaped() {
    $this->assertTransformed(
      '<p>Please don\'t use any <code>&lt;blink&gt;</code> tags</p>',
      'Please don\'t use any `<blink>` tags'
    );
  }

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

  #[@test]
  public function link_with_title() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Title">an example</a> inline link.</p>',
      'This is [an example](http://example.com/ "Title") inline link.'
    );
  }

  #[@test]
  public function link_without_title() {
    $this->assertTransformed(
      '<p><a href="http://example.net/">This link</a> has no title attribute.</p>',
      '[This link](http://example.net/) has no title attribute.'
    );
  }

  #[@test, @values(array(
  #  '[id]: http://example.com/  "Optional Title Here"',
  #  "[id]: http://example.com/  'Optional Title Here'",
  #  '[id]: http://example.com/  (Optional Title Here)',
  #))]
  public function reference_style_link($definition) {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>',
      "This is [an example][id] reference-style link.\n".
      $definition
    );
  }

  #[@test]
  public function references_are_case_insensitive() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>',
      "This is [an example][Id] reference-style link.\n".
      "[id]: http://example.com/  'Optional Title Here'"
    );
  }

  #[@test]
  public function definitions_are_case_insensitive() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>',
      "This is [an example][id] reference-style link.\n".
      "[Id]: http://example.com/  'Optional Title Here'"
    );
  }

  #[@test]
  public function implicit_link_name() {
    $this->assertTransformed(
      '<p><a href="http://google.com/">Google</a></p>',
      "[Google][]\n".
      "[Google]: http://google.com/"
    );
  }

  #[@test]
  public function implicit_link_name_with_spaces() {
    $this->assertTransformed(
      '<p>Visit <a href="http://daringfireball.net/">Daring Fireball</a> for more information.</p>',
      "Visit [Daring Fireball][] for more information.\n".
      "[Daring Fireball]: http://daringfireball.net/"
    );
  }

  #[@test]
  public function numeric_reference() {
    $this->assertTransformed(
      '<p>Traffic from <a href="http://google.com/" title="Google">Google</a> is high</p>',
      "Traffic from [Google] [1] is high\n".
      "[1]: http://google.com/        'Google'"
    );
  }

  #[@test]
  public function definitions_nicely_aligned() {
    $this->assertTransformed(
      '<p>Traffic from <a href="http://google.com/">Google</a> is higher than from <a href="http://search.yahoo.com/">Yahoo</a></p>',
      "Traffic from [Google][] is higher than from [Yahoo][]\n".
      "[google]: http://google.com/\n".
      "[yahoo]:  http://search.yahoo.com/"
    );
  }

  #[@test, @values(array(' ', '  ', '   '))]
  public function definitions_indented($indent) {
    $this->assertTransformed(
      '<p>Traffic from <a href="http://google.com/">Google</a> is higher than from <a href="http://search.yahoo.com/">Yahoo</a></p>',
      "Traffic from [Google][] is higher than from [Yahoo][]\n".
      $indent."[google]: http://google.com/\n".
      $indent."[yahoo]:  http://search.yahoo.com/"
    );
  }

  #[@test, @ignore('Not yet implemented')]
  public function link() {
    $this->assertTransformed(
      '<p><a href="http://example.com">http://example.com</a></p>',
      'http://example.com'
    );
  }

  #[@test]
  public function link_in_square_brackets() {
    $this->assertTransformed(
      '<p><a href="http://example.com">http://example.com</a></p>',
      '<http://example.com>'
    );
  }

  #[@test]
  public function url_with_parenthesis() {
    $this->assertTransformed(
      '<p>There\'s an <a href="http://en.memory-alpha.org/wiki/Darmok_(episode)">episode</a> of Star Trek: The Next Generation</p>',
      'There\'s an [episode](http://en.memory-alpha.org/wiki/Darmok_(episode)) of Star Trek: The Next Generation'
    );
  }

  #[@test]
  public function email_in_square_brackets() {
    $this->assertTransformed(
      '<p><a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:&#x61;&#x64;&#x64;&#x72;&#x65;'.
      '&#x73;&#x73;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;'.
      '&#x6f;&#x6d;">&#x61;&#x64;&#x64;&#x72;&#x65;&#x73;&#x73;&#x40;&#x65;&#x78;'.
      '&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;</a></p>',
      '<address@example.com>'
    );
  }

  #[@test]
  public function image_with_title() {
    $this->assertTransformed(
      '<p><img src="http://example.net/image.jpg" alt="This image" title="Title"/> has a title.</p>',
      '![This image](http://example.net/image.jpg "Title") has a title.'
    );
  }

  #[@test]
  public function image_without_title() {
    $this->assertTransformed(
      '<p><img src="http://example.net/image.jpg" alt="This image"/> has no title attribute.</p>',
      '![This image](http://example.net/image.jpg) has no title attribute.'
    );
  }

  #[@test, @ignore('Does not work yet; requires nesting inside handlers')]
  public function image_inside_link() {
    $this->assertTransformed(
      '<p>'.
        '<a href="http://travis-ci.org/xp-framework/xp-framework">'.
          '<img src="https://secure.travis-ci.org/xp-framework/xp-framework.png" alt="Build Status"/>'.
        '</a>'.
      '</p>',
      '[![Build Status](https://secure.travis-ci.org/xp-framework/xp-framework.png)](http://travis-ci.org/xp-framework/xp-framework)'
    );
  }

  #[@test]
  public function blockquote() {
    $this->assertTransformed(
      '<blockquote>Quoting</blockquote>',
      '> Quoting'
    );
  }

  #[@test, @values(array('* * *', '***', '*****'))]
  public function hr($input) {
    $this->assertTransformed('<hr/>', $input);
  }

  #[@test, @ignore('Not clear what the output should be')]
  public function blockquote_with_two_lines() {
    $this->assertTransformed(
      '<blockquote>Quoting 1Quoting 2</blockquote>',
      "> Quoting 1\n".
      "> Quoting 2\n"
    );
  }

}