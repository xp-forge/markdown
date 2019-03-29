<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Link;
use net\daringfireball\markdown\Rewriting;
use net\daringfireball\markdown\URLs;
use unittest\TestCase;

class URLsTest extends TestCase {

  #[@test, @values([
  #  new Link('http://example.org/'),
  #  new Link('https://example.org/'),
  #  new Image('http://example.org/image.png'),
  #])]
  public function resolve_absolute($link) {
    $this->assertEquals($link, (new URLs())->resolve($link, []));
  }

  #[@test]
  public function resolve_reference_link() {
    $link= new Link('http://example.org/');
    $this->assertEquals($link, (new URLs())->resolve(new Link('@example'), ['example' => $link]));
  }

  #[@test]
  public function resolve_reference_image() {
    $link= new Link('http://example.org/image.png');
    $this->assertEquals($link, (new URLs())->resolve(new Image('@example'), ['example' => $link]));
  }

  #[@test]
  public function resolve_non_existant_reference() {
    $link= new Link('@example');
    $this->assertEquals($link, (new URLs())->resolve($link, []));
  }

  #[@test]
  public function rewriting() {
    $tracking= newinstance(Rewriting::class, [], [
      'rewrite' => function($uri) { return '/tracking?url='.urlencode($uri); }
    ]);

    $this->assertEquals(
      new Link('/tracking?url=https%3A%2F%2Fexample.org%2F'),
      (new URLs())->rewriting($tracking)->resolve(new Link('https://example.org/'), [])
    );
  }

  #[@test]
  public function passing() {
    $proxy= newinstance(Rewriting::class, [], [
      'rewrite' => function($uri) { return '/proxy?url='.urlencode($uri); }
    ]);

    $this->assertEquals(
      new Image('/proxy?url=https%3A%2F%2Fexample.org%2F'),
      (new URLs())->passing($proxy)->resolve(new Image('https://example.org/'), [])
    );
  }
}