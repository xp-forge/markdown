<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Rewriting;
use unittest\{Test, TestCase, Values};

class RewritingTest extends TestCase {

  #[Test, Values(['http://example.org/', 'https://example.org/', '//example.org/',])]
  public function dereferring_for_absolute($uri) {
    $fixture= Rewriting::absolute()->links('/deref?url=%s');
    $this->assertEquals('/deref?url='.urlencode($uri), $fixture->href($uri));
  }

  #[Test, Values(['/image.png', 'static/image.png', 'static//image.png',])]
  public function no_dereferring_of_relative($uri) {
    $fixture= Rewriting::absolute()->links('/deref?url=%s');
    $this->assertEquals($uri, $fixture->href($uri));
  }

  #[Test, Values(['http://localhost/', 'http://LOCALHOST/', '//localhost/', 'https://test.localhost/', '//another.test.localhost/',])]
  public function no_dereferring_of_excluded($uri) {
    $fixture= Rewriting::absolute()->links('/deref?url=%s')->excluding(['localhost', '*.localhost']);
    $this->assertEquals($uri, $fixture->href($uri));
  }

  #[Test, Values(['https://evillocalhost/', 'https://evil-localhost/', 'http://localhosts/', 'http://localhost.evil/', '//localhost.evil/',])]
  public function excluded_must_strictly_match_host($uri) {
    $fixture= Rewriting::absolute()->links('/deref?url=%s')->excluding(['localhost', '*.localhost']);
    $this->assertEquals('/deref?url='.urlencode($uri), $fixture->href($uri));
  }

  #[Test, Values(['/image.png', 'static/image.png', 'static//image.png',])]
  public function caching_of_relative($uri) {
    $fixture= Rewriting::relative()->links('/caching?url=%s');
    $this->assertEquals('/caching?url='.urlencode($uri), $fixture->href($uri));
  }

  #[Test, Values(['http://example.org/', 'https://example.org/', '//example.org/',])]
  public function no_caching_of_absolute($uri) {
    $fixture= Rewriting::relative()->links('/caching?url=%s');
    $this->assertEquals($uri, $fixture->href($uri));
  }
}