<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Link, Text};
use test\Assert;
use test\{Test, Values};

class LinkTest extends MarkdownTest {

  #[Test]
  public function link_with_title() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Title">an example</a> inline link.</p>',
      'This is [an example](http://example.com/ "Title") inline link.'
    );
  }

  #[Test]
  public function link_without_title() {
    $this->assertTransformed(
      '<p><a href="http://example.net/">This link</a> has no title attribute.</p>',
      '[This link](http://example.net/) has no title attribute.'
    );
  }

  #[Test, Values(['[id]: http://example.com/  "Optional Title Here"', "[id]: http://example.com/  'Optional Title Here'", '[id]: http://example.com/  (Optional Title Here)',])]
  public function reference_style_link($definition) {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>',
      "This is [an example][id] reference-style link.\n".
      $definition
    );
  }

  #[Test]
  public function references_are_case_insensitive() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>',
      "This is [an example][Id] reference-style link.\n".
      "[id]: http://example.com/  'Optional Title Here'"
    );
  }

  #[Test]
  public function references_may_be_passed_to_transform_method() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/">an example</a> reference-style link.</p>',
      'This is [an example][id] reference-style link.',
      ['id' => new \net\daringfireball\markdown\Link('http://example.com/')]
    );
  }

  #[Test]
  public function definitions_are_case_insensitive() {
    $this->assertTransformed(
      '<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>',
      "This is [an example][id] reference-style link.\n".
      "[Id]: http://example.com/  'Optional Title Here'"
    );
  }

  #[Test]
  public function implicit_link_name() {
    $this->assertTransformed(
      '<p><a href="http://google.com/">Google</a></p>',
      "[Google][]\n".
      "[Google]: http://google.com/"
    );
  }

  #[Test]
  public function implicit_link_name_with_spaces() {
    $this->assertTransformed(
      '<p>Visit <a href="http://daringfireball.net/">Daring Fireball</a> for more information.</p>',
      "Visit [Daring Fireball][] for more information.\n".
      "[Daring Fireball]: http://daringfireball.net/"
    );
  }

  #[Test]
  public function numeric_reference() {
    $this->assertTransformed(
      '<p>Traffic from <a href="http://google.com/" title="Google">Google</a> is high</p>',
      "Traffic from [Google] [1] is high\n".
      "[1]: http://google.com/        'Google'"
    );
  }

  #[Test]
  public function definitions_nicely_aligned() {
    $this->assertTransformed(
      '<p>Traffic from <a href="http://google.com/">Google</a> is higher than from <a href="http://search.yahoo.com/">Yahoo</a></p>',
      "Traffic from [Google][] is higher than from [Yahoo][]\n".
      "[google]: http://google.com/\n".
      "[yahoo]:  http://search.yahoo.com/"
    );
  }

  #[Test, Values([' ', '  ', '   '])]
  public function definitions_indented($indent) {
    $this->assertTransformed(
      '<p>Traffic from <a href="http://google.com/">Google</a> is higher than from <a href="http://search.yahoo.com/">Yahoo</a></p>',
      "Traffic from [Google][] is higher than from [Yahoo][]\n".
      $indent."[google]: http://google.com/\n".
      $indent."[yahoo]:  http://search.yahoo.com/"
    );
  }

  #[Test]
  public function http_auto_link() {
    $this->assertTransformed(
      '<p><a href="http://example.com">http://example.com</a></p>',
      'http://example.com'
    );
  }

  #[Test]
  public function https_auto_link() {
    $this->assertTransformed(
      '<p><a href="https://example.com">https://example.com</a></p>',
      'https://example.com'
    );
  }

  #[Test]
  public function ftp_auto_link() {
    $this->assertTransformed(
      '<p><a href="ftp://example.com">ftp://example.com</a></p>',
      'ftp://example.com'
    );
  }

  #[Test]
  public function auto_link_in_header() {
    $this->assertTransformed(
      '<h1><a href="http://example.com">http://example.com</a></h1>',
      '# http://example.com'
    );
  }

  /**
   * Returns sentence delimiters for use as parameters
   *
   * @return string[]
   */
  public function delimiters() { return ['.', '?', ',', ';', '!']; }

  #[Test, Values(from: 'delimiters')]
  public function auto_link_in_sentence_at_end($delimiter) {
    $this->assertTransformed(
      '<p>This is a link to <a href="http://example.com">http://example.com</a>'.$delimiter.'</p>',
      'This is a link to http://example.com'.$delimiter
    );
  }

  #[Test, Values(from: 'delimiters')]
  public function auto_link_in_sentence($delimiter) {
    $this->assertTransformed(
      '<p>This is a link to <a href="http://example.com">http://example.com</a>'.$delimiter.' It ...</p>',
      'This is a link to http://example.com'.$delimiter.' It ...'
    );
  }

  #[Test, Values(from: 'delimiters')]
  public function auto_link_with_delimiters_in_sentence($delimiter) {
    $this->assertTransformed(
      '<p>This is a link to <a href="http://example.com/d;n=x/?a=b,c.d">http://example.com/d;n=x/?a=b,c.d</a>'.$delimiter.' It ...</p>',
      'This is a link to http://example.com/d;n=x/?a=b,c.d'.$delimiter.' It ...'
    );
  }

  #[Test]
  public function link_in_square_brackets() {
    $this->assertTransformed(
      '<p><a href="http://example.com">http://example.com</a></p>',
      '<http://example.com>'
    );
  }

  #[Test]
  public function url_with_parenthesis() {
    $this->assertTransformed(
      '<p>There\'s an <a href="http://en.memory-alpha.org/wiki/Darmok_(episode)">episode</a> of Star Trek: The Next Generation</p>',
      'There\'s an [episode](http://en.memory-alpha.org/wiki/Darmok_(episode)) of Star Trek: The Next Generation'
    );
  }

  #[Test]
  public function email_in_square_brackets() {
    $this->assertTransformed(
      '<p><a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:&#x61;&#x64;&#x64;&#x72;&#x65;'.
      '&#x73;&#x73;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;'.
      '&#x6f;&#x6d;">&#x61;&#x64;&#x64;&#x72;&#x65;&#x73;&#x73;&#x40;&#x65;&#x78;'.
      '&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;</a></p>',
      '<address@example.com>'
    );
  }

  #[Test]
  public function link_nested_in_emphasized() {
    $this->assertTransformed(
      '<p><em><a href="http://example.net/">A link</a></em></p>',
      '*[A link](http://example.net/)*'
    );
  }

  #[Test]
  public function text_in_square_braces() {
    $this->assertTransformed(
      '<p>This is [not a link], man.</p>',
      'This is [not a link], man.'
    );
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'net.daringfireball.markdown.Link(url= http://example.com, text= null, title= null)',
      (new Link('http://example.com'))->toString()
    );
  }

  #[Test]
  public function string_representation_with_text() {
    $t= new Text('example');
    Assert::equals(
      'net.daringfireball.markdown.Link(url= http://example.com, text= '.$t->toString().', title= null)',
      (new Link('http://example.com', $t))->toString()
    );
  }

  #[Test]
  public function string_representation_with_title() {
    Assert::equals(
      'net.daringfireball.markdown.Link(url= http://example.com, text= null, title= "Test")',
      (new Link('http://example.com', null, 'Test'))->toString()
    );
  }

  #[Test, Values(['[comment]: <> (his is a comment, it will not be included)', '[//]: <> This is also a comment.)', '[//]: # (This may be the most platform independent comment)',])]
  public function using_link_labels_as_comments($input) {
    $this->assertTransformed('<p></p>', $input);
  }
}