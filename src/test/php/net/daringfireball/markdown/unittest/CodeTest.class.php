<?php namespace net\daringfireball\markdown\unittest;

class CodeTest extends MarkdownTest {

  #[@test]
  public function code() {
    $this->assertTransformed(
      '<p>Use the <code>printf()</code> function</p>',
      'Use the `printf()` function'
    );
  }

  #[@test]
  public function html_inside_code_is_escaped() {
    $this->assertTransformed(
      '<p>Please don\'t use any <code>&lt;blink&gt;</code> tags</p>',
      'Please don\'t use any `<blink>` tags'
    );
  }
}