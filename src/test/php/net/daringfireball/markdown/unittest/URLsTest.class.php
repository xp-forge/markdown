<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Link;
use net\daringfireball\markdown\Rewriting;
use net\daringfireball\markdown\URLs;
use unittest\TestCase;

class URLsTest extends TestCase {

  #[@test, @values([
  #  'http://example.org/',
  #  'https://example.org/',
  #  '//example.org/',
  #  '/image.png',
  #  'static/image.png',
  #  'static//image.png',
  #])]
  public function href_of_link($url) {
    $this->assertEquals($url, (new URLs())->href(new Link($url)));
  }

  #[@test, @values([
  #  'http://example.org/',
  #  'https://example.org/',
  #  '//example.org/',
  #])]
  public function dereferring_for_absolute($uri) {
    $urls= new URLs(Rewriting::absolute()->links('/deref?url=%s'));
    $this->assertEquals('/deref?url='.urlencode($uri), $urls->href(new Link($uri)));
  }

  #[@test, @values([
  #  '/image.png',
  #  'static/image.png',
  #  'static//image.png',
  #])]
  public function no_dereferring_of_relative($uri) {
    $urls= new URLs(Rewriting::absolute()->links('/deref?url=%s'));
    $this->assertEquals($uri, $urls->href(new Link($uri)));
  }

  #[@test, @values([
  #  'http://localhost/',
  #  'http://LOCALHOST/',
  #  '//localhost/',
  #  'https://test.localhost/',
  #  '//another.test.localhost/',
  #])]
  public function no_dereferring_of_excluded($uri) {
    $urls= new URLs(Rewriting::absolute()->links('/deref?url=%s')->exclude(['localhost', '*.localhost']));
    $this->assertEquals($uri, $urls->href(new Link($uri)));
  }

  #[@test, @values([
  #  'https://evillocalhost/',
  #  'https://evil-localhost/',
  #  'http://localhosts/',
  #  'http://localhost.evil/',
  #  '//localhost.evil/',
  #])]
  public function excluded_must_strictly_match_host($uri) {
    $urls= new URLs(Rewriting::absolute()->links('/deref?url=%s')->exclude(['localhost', '*.localhost']));
    $this->assertEquals('/deref?url='.urlencode($uri), $urls->href(new Link($uri)));
  }

  #[@test, @values([
  #  'http://example.org/blank.gif',
  #  'https://example.org/blank.gif',
  #])]
  public function src_of_image($url) {
    $this->assertEquals($url, (new URLs())->src(new Image($url)));
  }
}