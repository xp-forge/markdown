<?php namespace net\daringfireball\markdown\unittest;

/**
 * Tests String input
 */
class StringInputTest extends InputTest {

  /**
   * Create and return new fixture.
   *
   * @param   string source
   * @return  net.daringfireball.markdown.Input
   */
  protected function newFixture($source) {
    return new \net\daringfireball\markdown\StringInput($source);
  }
}