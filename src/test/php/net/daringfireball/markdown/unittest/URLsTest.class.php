<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Image, Link, Rewriting, URLs};
use test\Assert;
use test\{Test, TestCase, Values};

class URLsTest {

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

  #[Test, Values(from: 'uris')]
  public function href_of_link($url) {
    Assert::equals($url, (new URLs())->href(new Link($url)));
  }

  #[Test, Values(from: 'uris')]
  public function src_of_image($url) {
    Assert::equals($url, (new URLs())->src(new Image($url)));
  }

  #[Test, Values(from: 'uris')]
  public function tracking_links($url) {
    Assert::equals(
      '/tracking?url='.urlencode($url),
      (new URLs(Rewriting::all()->links('/tracking?url=%s')))->href(new Link($url))
    );
  }
}