<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Image, Link, Rewriting, URLs};
use unittest\{Test, TestCase, Values};

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

  #[Test, Values('uris')]
  public function href_of_link($url) {
    $this->assertEquals($url, (new URLs())->href(new Link($url)));
  }

  #[Test, Values('uris')]
  public function src_of_image($url) {
    $this->assertEquals($url, (new URLs())->src(new Image($url)));
  }

  #[Test, Values('uris')]
  public function tracking_links($url) {
    $this->assertEquals(
      '/tracking?url='.urlencode($url),
      (new URLs(Rewriting::all()->links('/tracking?url=%s')))->href(new Link($url))
    );
  }
}