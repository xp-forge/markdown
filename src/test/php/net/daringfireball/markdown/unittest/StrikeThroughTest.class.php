<?php namespace net\daringfireball\markdown\unittest;

class StrikeThroughTest extends MarkdownTest {

  #[@test]
  public function strikethrough() {
    $this->assertTransformed('<p><del>Hello</del></p>', '~~Hello~~');
  }
}