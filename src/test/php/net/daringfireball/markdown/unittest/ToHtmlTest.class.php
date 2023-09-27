<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Email, Image, Link, Paragraph, ParseTree, Text, ToHtml, URLs};
use test\Assert;
use test\{Test, TestCase, Values};

class ToHtmlTest {

  #[Test]
  public function can_create() {
    new ToHtml();
  }

  #[Test]
  public function emit_parse_tree() {
    $tree= new ParseTree([new Paragraph([new Text('Hello World')])]);
    Assert::equals('<p>Hello World</p>', $tree->emit(new ToHtml()));
  }

  #[Test]
  public function special_chars_are_escaped() {
    Assert::equals('4 &lt; 5', (new Text('4 < 5'))->emit(new ToHtml()));
  }

  #[Test]
  public function one_trailing_space() {
    Assert::equals('Test ', (new Text('Test '))->emit(new ToHtml()));
  }

  #[Test]
  public function emails_are_encoded() {
    $encoded= '&#x74;&#x69;&#x6d;&#x6d;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;';
    Assert::equals(
      '<a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:'.$encoded.'">'.$encoded.'</a>',
      (new Email('timm@example.com'))->emit(new ToHtml())
    );
  }

  #[Test]
  public function urls_member_accessible_to_subclasses() {
    $fixture= new class() extends ToHtml {
      public function link() { return $this->urls->href(new Link('https://example.com/')); }
    };
    Assert::equals('https://example.com/', $fixture->link());
  }

  #[Test]
  public function derefer_links() {
    $tree= new ParseTree([new Paragraph([new Link('https://example.com/', new Text('External link'))])]);

    Assert::equals(
      '<p><a href="/deref?url=https%3A%2F%2Fexample.com%2F">External link</a></p>',
      $tree->emit(new ToHtml(new class() extends URLs {
        public function href($link) { return '/deref?url='.urlencode($link->url); }
      }))
    );
  }

  #[Test]
  public function proxy_images() {
    $tree= new ParseTree([new Paragraph([new Image('https://example.com/test.png', new Text('External image'))])]);

    Assert::equals(
      '<p><img src="/proxy?url=https%3A%2F%2Fexample.com%2Ftest.png" alt="External image" /></p>',
      $tree->emit(new ToHtml(new class() extends URLs {
        public function src($image) { return '/proxy?url='.urlencode($image->url); }
      }))
    );
  }
}