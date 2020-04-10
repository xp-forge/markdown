<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Image, Link, Rewriting, URLs};
use unittest\TestCase;

class URLsTest extends TestCase {

  /** @return string[][] */
  private function uris() {
    return [
      ['http://example.org/'],
      ['https://example.org/'],
      ['//example.org/'],
      ['/image.png'],
      ['static/image.png'],
      ['static//image.png'],
    ];
  }

  #[@test, @values('uris')]
  public function href_of_link($url) {
    $this->assertEquals($url, (new URLs())->href(new Link($url)));
  }

  #[@test, @values('uris')]
  public function src_of_image($url) {
    $this->assertEquals($url, (new URLs())->src(new Image($url)));
  }

  #[@test, @values('uris')]
  public function tracking_links($url) {
    $this->assertEquals(
      '/tracking?url='.urlencode($url),
      (new URLs(Rewriting::all()->links('/tracking?url=%s')))->href(new Link($url))
    );
  }
}