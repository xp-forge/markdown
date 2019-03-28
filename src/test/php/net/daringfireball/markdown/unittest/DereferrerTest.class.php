<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Dereferrer;
use unittest\TestCase;

class DereferrerTest extends TestCase {

  #[@test, @values([
  #  'http://example.org/',
  #  'https://example.org/',
  #  '//example.org/',
  #])]
  public function dereferring_for_absolute($uri) {
    $this->assertEquals('/deref?'.urlencode($uri), (new Dereferrer('/deref?{0}'))->rewrite($uri));
  }

  #[@test, @values([
  #  '/image.png',
  #  'static/image.png',
  #  'static//image.png',
  #])]
  public function no_dereferring_of_relative($uri) {
    $this->assertEquals($uri, (new Dereferrer('/deref?{0}'))->rewrite($uri));
  }
}