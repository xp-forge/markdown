<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Rewriter;
use unittest\TestCase;

class RewriterTest extends TestCase {

  #[@test, @values([
  #  'http://example.org/',
  #  'https://example.org/',
  #  '//example.org/',
  #])]
  public function dereferring_for_absolute($uri) {
    $this->assertEquals('/deref?'.urlencode($uri), (new Rewriter('/deref?%s'))->rewrite($uri));
  }

  #[@test, @values([
  #  '/image.png',
  #  'static/image.png',
  #  'static//image.png',
  #])]
  public function no_dereferring_of_relative($uri) {
    $this->assertEquals($uri, (new Rewriter('/deref?%s'))->rewrite($uri));
  }

  #[@test, @values([
  #  'http://localhost/',
  #  'http://LOCALHOST/',
  #  '//localhost/',
  #  'https://test.localhost/',
  #  '//another.test.localhost/',
  #])]
  public function no_dereferring_of_excluded($uri) {
    $this->assertEquals($uri, (new Rewriter('/deref?%s', ['localhost', '*.localhost']))->rewrite($uri));
  }

  #[@test, @values([
  #  'https://evillocalhost/',
  #  'https://evil-localhost/',
  #  'http://localhosts/',
  #  'http://localhost.evil/',
  #  '//localhost.evil/',
  #])]
  public function excluded_must_strictly_match_host($uri) {
    $this->assertEquals('/deref?'.urlencode($uri), (new Rewriter('/deref?%s', ['localhost', '*.localhost']))->rewrite($uri));
  }
}