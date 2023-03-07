<?php namespace net\daringfireball\markdown\unittest;

use io\streams\{MemoryInputStream, TextReader};
use net\daringfireball\markdown\{Link, Markdown, Paragraph, ParseTree, ReaderInput, StringInput, ToHtml};
use test\Assert;
use test\Test;

class MarkdownClassTest extends MarkdownTest {

  #[Test]
  public function can_create() {
    new Markdown();
  }

  #[Test]
  public function parse_string() {
    Assert::equals(
      new ParseTree([new Paragraph()], []),
      (new Markdown())->parse('')
    );
  }

  #[Test]
  public function transform_string() {
    Assert::equals('<p></p>', (new Markdown())->transform(''));
  }

  #[Test]
  public function transform_reader() {
    Assert::equals('<p></p>', (new Markdown())->transform(
      new TextReader(new MemoryInputStream(''))
    ));
  }

  #[Test]
  public function transform_string_input() {
    Assert::equals('<p></p>', (new Markdown())->transform(new StringInput('')));
  }

  #[Test]
  public function transform_reader_input() {
    Assert::equals('<p></p>', (new Markdown())->transform(new ReaderInput(
      new TextReader(new MemoryInputStream(''))
    )));
  }

  #[Test]
  public function transform_given_urls() {
    Assert::equals(
      '<p><a href="http://example.com">Link</a> to <a href="http://xp-framework.net">XP</a></p>',
      (new Markdown())->transform('[Link][] to [XP][]', [
        'link' => new Link('http://example.com'),
        'XP'   => new Link('http://xp-framework.net')
      ])
    );
  }

  #[Test]
  public function transform_with_emitter() {
    Assert::equals(
      '<p><span>Test</span></p>',
      (new Markdown())->transform('Test', [], new class() extends ToHtml {
        public function emitText($text, $definitions) {
          return '<span>'.parent::emitText($text, $definitions).'</span>';
        }
      })
    );
  }
}