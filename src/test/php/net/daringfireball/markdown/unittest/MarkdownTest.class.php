<?php namespace net\daringfireball\markdown\unittest;

/**
 * Base class for other markdown tests. Creates fixture and 
 * provides assertion helper.
 */
abstract class MarkdownTest extends \unittest\TestCase {
  protected $fixture;

  /**
   * Create fixture
   */
  public function setUp() {
    $this->fixture= new \net\daringfireball\markdown\Markdown();
  }

  /**
   * Assertion helper
   *
   * @param  string $expected
   * @param  string $input
   * @throws unittest.AssertionFailedError
   */
  protected function assertTransformed($expected, $input) {
    $this->assertEquals($expected, $this->fixture->transform($input));
  }
}