<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Markdown;

/**
 * Base class for other markdown tests. Creates fixture and 
 * provides assertion helper.
 */
abstract class MarkdownTest extends \unittest\TestCase {
  protected $fixture;

  /** @return void */
  public function setUp() {
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
    $this->assertEquals($expected, $this->fixture->transform($input, $urls));
  }
}