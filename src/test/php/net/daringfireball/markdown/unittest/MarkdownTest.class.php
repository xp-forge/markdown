<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Markdown;
use test\{Assert, Before};

/**
 * Base class for other markdown tests. Creates fixture and 
 * provides assertion helper.
 */
abstract class MarkdownTest {
  protected $fixture;

  #[Before]
  public function fixture() {
    $this->fixture= new Markdown();
  }

  /**
   * Assertion helper
   *
   * @param  string $expected
   * @param  string $input
   * @param  [:net.daringfireball.markdown.Link] $urls
   * @throws unittest.AssertionFailedError
   */
  protected function assertTransformed($expected, $input, $urls= []) {
    Assert::equals($expected, $this->fixture->transform($input, $urls));
  }
}