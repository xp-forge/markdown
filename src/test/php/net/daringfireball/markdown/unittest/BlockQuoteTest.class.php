<?php namespace net\daringfireball\markdown\unittest;

class BlockQuoteTest extends MarkdownTest {

  #[@test]
  public function single_line() {
    $this->assertTransformed(
      '<blockquote>Quoting</blockquote>',
      '> Quoting'
    );
  }

  #[@test]
  public function single_line_with_markup() {
    $this->assertTransformed(
      '<blockquote><em>Quoting</em></blockquote>',
      '> *Quoting*'
    );
  }

  #[@test, @ignore('Not clear what the output should be')]
  public function two_lines() {
    $this->assertTransformed(
      '<blockquote>Quoting 1Quoting 2</blockquote>',
      "> Quoting 1\n".
      "> Quoting 2\n"
    );
  }
}