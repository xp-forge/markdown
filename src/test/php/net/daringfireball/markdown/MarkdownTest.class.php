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
    // \util\cmd\Console::writeLine('"', $input, '" => ', new \lang\types\Bytes(\Michelf\Markdown::defaultTransform($input)));
    $this->assertEquals($expected, $this->fixture->transform($input));
  }

  #[@test, @values(array('', ' ', 'Hello World'))]
  public function transforming_plain_text_equals_itself($value) {
    $this->assertTransformed($value, $value);
  }

  #[@test, @values(array('<', '>', '&', '"', "'"))]
  public function special_characters_are_escaped($value) {
    $this->assertTransformed(htmlspecialchars($value), $value);
  }

  #[@test, @values(array('AT&amp;T', '&quot;'))]
  public function htmlentities_are_left_untouched($value) {
    $this->assertTransformed($value, $value);
  }

  #[@test]
  public function first_level_header() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header');
  }

  #[@test]
  public function second_level_header() {
    $this->assertTransformed('<h2>A Second Level Header</h2>', '## A Second Level Header');
  }

  #[@test]
  public function third_level_header() {
    $this->assertTransformed('<h3>A Third Level Header</h3>', '### A Third Level Header');
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
  public function code() {
    $this->assertTransformed(
      'Use the <code>printf()</code> function',
      'Use the `printf()` function'
    );
  }

  #[@test]
  public function code_is_escaped() {
    $this->assertTransformed(
      'Please don\'t use any <code>&lt;blink&gt;</code> tags',
      'Please don\'t use any `<blink>` tags'
    );
  }

  #[@test, @values(array('*Hello*', '_Hello_'))]
  public function emphasis($input) {
    $this->assertTransformed('<em>Hello</em>', $input);
  }

  #[@test, @values(array('*Hello* World', '_Hello_ World'))]
  public function emphasized_first_word($input) {
    $this->assertTransformed('<em>Hello</em> World', $input);
  }

  #[@test, @values(array('Hello *World*', 'Hello _World_'))]
  public function emphasized_second_word($input) {
    $this->assertTransformed('Hello <em>World</em>', $input);
  }

  #[@test]
  public function emphasis_can_be_used_in_the_middle_of_a_word() {
    $this->assertTransformed('un<em>frigging</em>believable', 'un*frigging*believable');
  }

  #[@test, @values(array('**Hello**', '__Hello__'))]
  public function strong($input) {
    $this->assertTransformed('<strong>Hello</strong>', $input);
  }

  #[@test, @values(array('**Hello** World', '__Hello__ World'))]
  public function strong_first_word($input) {
    $this->assertTransformed('<strong>Hello</strong> World', $input);
  }

  #[@test, @values(array('Hello **World**', 'Hello __World__'))]
  public function strong_second_word($input) {
    $this->assertTransformed('Hello <strong>World</strong>', $input);
  }

  #[@test]
  public function link_with_title() {
    $this->assertTransformed(
      'This is <a href="http://example.com/" title="Title">an example</a> inline link.',
      'This is [an example](http://example.com/ "Title") inline link.'
    );
  }

  #[@test]
  public function link_without_title() {
    $this->assertTransformed(
      '<a href="http://example.net/">This link</a> has no title attribute.',
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
      'This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.',
      "This is [an example][id] reference-style link.\n".
      $definition
    );
  }

  #[@test]
  public function references_are_case_insensitive() {
    $this->assertTransformed(
      'This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.',
      "This is [an example][Id] reference-style link.\n".
      "[id]: http://example.com/  'Optional Title Here'"
    );
  }

  #[@test]
  public function definitions_are_case_insensitive() {
    $this->assertTransformed(
      'This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.',
      "This is [an example][id] reference-style link.\n".
      "[Id]: http://example.com/  'Optional Title Here'"
    );
  }

  #[@test]
  public function implicit_link_name() {
    $this->assertTransformed(
      '<a href="http://google.com/">Google</a>',
      "[Google][]\n".
      "[Google]: http://google.com/"
    );
  }

  #[@test]
  public function implicit_link_name_with_spaces() {
    $this->assertTransformed(
      'Visit <a href="http://daringfireball.net/">Daring Fireball</a> for more information.',
      "Visit [Daring Fireball][] for more information.\n".
      "[Daring Fireball]: http://daringfireball.net/"
    );
  }

  #[@test]
  public function numeric_reference() {
    $this->assertTransformed(
      'Traffic from <a href="http://google.com/" title="Google">Google</a> is high',
      "Traffic from [Google] [1] is high\n".
      "[1]: http://google.com/        'Google'"
    );
  }

  #[@test]
  public function definitions_nicely_aligned() {
    $this->assertTransformed(
      'Traffic from <a href="http://google.com/">Google</a> is higher than from <a href="http://search.yahoo.com/">Yahoo</a>',
      "Traffic from [Google][] is higher than from [Yahoo][]\n".
      "[google]: http://google.com/\n".
      "[yahoo]:  http://search.yahoo.com/"
    );
  }

  #[@test, @values(array(' ', '  ', '   '))]
  public function definitions_indented($indent) {
    $this->assertTransformed(
      'Traffic from <a href="http://google.com/">Google</a> is higher than from <a href="http://search.yahoo.com/">Yahoo</a>',
      "Traffic from [Google][] is higher than from [Yahoo][]\n".
      $indent."[google]: http://google.com/\n".
      $indent."[yahoo]:  http://search.yahoo.com/"
    );
  }

  #[@test, @ignore('Not yet implemented')]
  public function link() {
    $this->assertTransformed(
      '<a href="http://example.com">http://example.com</a>',
      'http://example.com'
    );
  }

  #[@test]
  public function link_in_square_brackets() {
    $this->assertTransformed(
      '<a href="http://example.com">http://example.com</a>',
      '<http://example.com>'
    );
  }

  #[@test]
  public function email_in_square_brackets() {
    $this->assertTransformed(
      '<a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:&#x61;&#x64;&#x64;&#x72;&#x65;'.
      '&#x73;&#x73;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;'.
      '&#x6f;&#x6d;">&#x61;&#x64;&#x64;&#x72;&#x65;&#x73;&#x73;&#x40;&#x65;&#x78;'.
      '&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;</a>',
      '<address@example.com>'
    );
  }

  #[@test]
  public function image_with_title() {
    $this->assertTransformed(
      '<img src="http://example.net/image.jpg" alt="This image" title="Title"/> has a title.',
      '![This image](http://example.net/image.jpg "Title") has a title.'
    );
  }

  #[@test]
  public function image_without_title() {
    $this->assertTransformed(
      '<img src="http://example.net/image.jpg" alt="This image"/> has no title attribute.',
      '![This image](http://example.net/image.jpg) has no title attribute.'
    );
  }

  #[@test, @ignore('Does not work yet; requires nesting inside handlers')]
  public function image_inside_link() {
    $this->assertTransformed(
      '<a href="http://travis-ci.org/xp-framework/xp-framework">'.
      '<img src="https://secure.travis-ci.org/xp-framework/xp-framework.png" alt="Build Status"/>'.
      '</a>',
      '[![Build Status](https://secure.travis-ci.org/xp-framework/xp-framework.png)](http://travis-ci.org/xp-framework/xp-framework)'
    );
  }
}