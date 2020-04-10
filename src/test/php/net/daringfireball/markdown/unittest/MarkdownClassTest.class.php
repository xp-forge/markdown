<?php namespace net\daringfireball\markdown\unittest;

use io\streams\{MemoryInputStream, TextReader};
use net\daringfireball\markdown\{Link, Markdown, Paragraph, ParseTree, ReaderInput, StringInput, ToHtml};

class MarkdownClassTest extends MarkdownTest {

  #[@test]
  public function can_create() {
    new Markdown();
  }

  #[@test]
  public function parse_string() {
    $this->assertEquals(
      new ParseTree([new Paragraph()], []),
      (new Markdown())->parse('')
    );
  }

  #[@test]
  public function transform_string() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(''));
  }

  #[@test]
  public function transform_reader() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(
      new TextReader(new MemoryInputStream(''))
    ));
  }

  #[@test]
  public function transform_string_input() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(new StringInput('')));
  }

  #[@test]
  public function transform_reader_input() {
    $this->assertEquals('<p></p>', (new Markdown())->transform(new ReaderInput(
      new TextReader(new MemoryInputStream(''))
    )));
  }

  #[@test]
  public function transform_given_urls() {
    $this->assertEquals(
      '<p><a href="http://example.com">Link</a> to <a href="http://xp-framework.net">XP</a></p>',
      (new Markdown())->transform('[Link][] to [XP][]', [
        'link' => new Link('http://example.com'),
        'XP'   => new Link('http://xp-framework.net')
      ])
    );
  }

  #[@test]
  public function transform_with_emitter() {
    $this->assertEquals(
      '<p><span>Test</span></p>',
      (new Markdown())->transform('Test', [], new class() extends ToHtml {
        public function emitText($text, $definitions) {
          return '<span>'.parent::emitText($text, $definitions).'</span>';
        }
      })
    );
  }
}