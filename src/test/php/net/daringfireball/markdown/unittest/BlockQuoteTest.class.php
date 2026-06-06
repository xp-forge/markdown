<?php namespace net\daringfireball\markdown\unittest;

use test\Assert;
use test\{Ignore, Test};

class BlockQuoteTest extends MarkdownTest {

  #[Test]
  public function single_line() {
    $this->assertTransformed(
      '<blockquote>Quoting</blockquote>',
      '> Quoting'
    );
  }

  #[Test]
  public function single_line_with_markup() {
    $this->assertTransformed(
      '<blockquote><em>Quoting</em></blockquote>',
      '> *Quoting*'
    );
  }

  #[Test]
  public function two_lines() {
    $this->assertTransformed(
      '<blockquote>Quoting 1Quoting 2</blockquote>',
      "> Quoting 1\n".
      "> Quoting 2\n"
    );
  }

  #[Test]
  public function text_before() {
    $this->assertTransformed(
      '<p>Before</p><blockquote>Quoting</blockquote>',
      "Before\n".
      "> Quoting"
    );
  }

  #[Test]
  public function text_after() {
    $this->assertTransformed(
      '<blockquote>Quoting</blockquote><p>After</p>',
      "> Quoting\n".
      "After"
    );
  }

  #[Test]
  public function list_inside() {
    $this->assertTransformed(
      '<blockquote>Intro<ol><li>a</li><li>b</li></ol>Outro</blockquote>',
      "> Intro\n".
      "> 1. a\n".
      "> 2. b\n".
      "> Outro\n"
    );
  }
}