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
  public function single_backtick_in_a_code_span() {
    $this->assertTransformed(
      '<p><code>`</code></p>',
      '`` ` ``'
    );
  }

  #[@test]
  public function backtick_delimited_string_in_a_code_span() {
    $this->assertTransformed(
      '<p><code>`foo`</code></p>',
      '`` `foo` ``'
    );
  }

  #[@test]
  public function code_span_with_leading_space_and_no_trailing_space() {
    $this->assertTransformed(
      '<p><code>$files= [];</code></p>',
      '`` $files= [];``'
    );
  }

  #[@test]
  public function code_span_with_leading_space_and_no_trailing_space() {
    $this->assertTransformed(
      '<p>This <code>$files= [];</code> will initialize "files" to an empty array</p>',
      'This `` $files= [];`` will initialize "files" to an empty array'
    );
  }

  #[@test]
  public function html_inside_code_is_escaped() {
    $this->assertTransformed(
      '<p>Please don\'t use any <code>&lt;blink&gt;</code> tags</p>',
      'Please don\'t use any `<blink>` tags'
    );
  }

  #[@test]
  public function two_code_blocks() {
    $this->assertTransformed(
      '<p><code>&amp;#8212;</code> is the decimal-encoded equivalent of <code>&amp;mdash;</code>.</p>',
      '`&#8212;` is the decimal-encoded equivalent of `&mdash;`.'
    );
  }
}