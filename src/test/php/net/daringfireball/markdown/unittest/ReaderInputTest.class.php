<?php namespace net\daringfireball\markdown\unittest;

use test\Assert;
/**
 * Tests Reader input
 */
class ReaderInputTest extends InputTest {

  /**
   * Create and return new fixture.
   *
   * @param   string source
   * @return  net.daringfireball.markdown.Input
   */
  protected function newFixture($source) {
    return new \net\daringfireball\markdown\ReaderInput(
      new \io\streams\TextReader(new \io\streams\MemoryInputStream($source))
    );
  }
}