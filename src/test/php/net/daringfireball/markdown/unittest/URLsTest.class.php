<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Link;
use net\daringfireball\markdown\URLs;
use unittest\TestCase;

class URLsTest extends TestCase {

  #[@test, @values([
  #  'http://example.org/',
  #  'https://example.org/',
  #])]
  public function href_of_link($url) {
    $this->assertEquals($url, (new URLs())->href(new Link($url)));
  }

  #[@test, @values([
  #  'http://example.org/blank.gif',
  #  'https://example.org/blank.gif',
  #])]
  public function src_of_image($url) {
    $this->assertEquals($url, (new URLs())->src(new Image($url)));
  }
}