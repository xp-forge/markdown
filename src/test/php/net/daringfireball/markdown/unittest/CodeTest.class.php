<?php namespace net\daringfireball\markdown\unittest;

class CodeTest extends MarkdownTest {

  #[@test]
  public function single_backtick() {
    $this->assertTransformed(
      '<p>Use the <code>printf()</code> function</p>',
      'Use the `printf()` function'
    );
  }

  #[@test]
  public function literal_backtick_inside_code_with_multiple_backticks() {
    $this->assertTransformed(
      '<p><code>There is a literal backtick (`) here.</code></p>',
      '``There is a literal backtick (`) here.``'
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