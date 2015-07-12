<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Markdown;
use net\daringfireball\markdown\StringInput;
use net\daringfireball\markdown\ReaderInput;
use net\daringfireball\markdown\Link;

class MarkdownClassTest extends MarkdownTest {

  #[@test]
  public function can_create() {
    new Markdown();
  }

  #[@test]
  public function transform_string() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(''));
  }

  #[@test]
  public function transform_string_input() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(new StringInput('')));
  }

  #[@test]
  public function transform_reader_input() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(new ReaderInput(
      new \io\streams\TextReader(new \io\streams\MemoryInputStream(''))
    )));
  }

  #[@test]
  public function transform_given_urls() {
    $this->assertEquals(
      '<p><a href="http://example.com">Link</a> to <a href="http://xp-framework.net">XP</a></p>',
      (new Markdown())->transform('[Link][] to [XP][]', array(
        'link' => new Link('http://example.com'),
        'XP'   => new Link('http://xp-framework.net')
      ))
    );
  }
}