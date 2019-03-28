<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Link;
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
  public function resolve_non_existant_reference() {
    $link= new Link('@example');
    $this->assertEquals($link, (new URLs())->resolve($link, []));
  }
}