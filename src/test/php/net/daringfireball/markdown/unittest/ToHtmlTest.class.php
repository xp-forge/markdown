<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Email;
use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Link;
use net\daringfireball\markdown\Paragraph;
use net\daringfireball\markdown\ParseTree;
use net\daringfireball\markdown\Text;
use net\daringfireball\markdown\ToHtml;
use net\daringfireball\markdown\URLs;

class ToHtmlTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new ToHtml();
  }

  #[@test]
  public function emit_parse_tree() {
    $tree= new ParseTree([new Paragraph([new Text('Hello World')])]);
    $this->assertEquals('<p>Hello World</p>', $tree->emit(new ToHtml()));
  }

  #[@test]
  public function special_chars_are_escaped() {
    $this->assertEquals('4 &lt; 5', (new Text('4 < 5'))->emit(new ToHtml()));
  }

  #[@test]
  public function one_trailing_space() {
    $this->assertEquals('Test ', (new Text('Test '))->emit(new ToHtml()));
  }

  #[@test, @values(['  ', '   '])]
  public function manual_line_break_with_two_or_more_spaces($spaces) {
    $this->assertEquals('Test<br/>', (new Text('Test'.$spaces))->emit(new ToHtml()));
  }

  #[@test]
  public function emails_are_encoded() {
    $encoded= '&#x74;&#x69;&#x6d;&#x6d;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;';
    $this->assertEquals(
      '<a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:'.$encoded.'">'.$encoded.'</a>',
      (new Email('timm@example.com'))->emit(new ToHtml())
    );
  }

  #[@test]
  public function derefer_links() {
    $tree= new ParseTree([new Paragraph([new Link('https://example.com/', new Text('External link'))])]);

    $this->assertEquals(
      '<p><a href="/deref?url=https%3A%2F%2Fexample.com%2F">External link</a></p>',
      $tree->emit(new ToHtml(newinstance(URLs::class, [], [
        'href' => function($link) { return '/deref?url='.urlencode($link->url); }
      ])))
    );
  }

  #[@test]
  public function proxy_images() {
    $tree= new ParseTree([new Paragraph([new Image('https://example.com/test.png', new Text('External image'))])]);

    $this->assertEquals(
      '<p><img src="/proxy?url=https%3A%2F%2Fexample.com%2Ftest.png" alt="External image"/></p>',
      $tree->emit(new ToHtml(newinstance(URLs::class, [], [
        'src' => function($image) { return '/proxy?url='.urlencode($image->url); }
      ])))
    );
  }
}