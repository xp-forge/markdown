<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Code, CodeBlock, Text};
use test\{Assert, Test, Values};

class CodeTest extends MarkdownTest {

  #[Test]
  public function code_of_codeblock() {
    $block= new CodeBlock('bash');
    $block->add(new Text('#!/bin/sh'));
    $block->add(new Text('echo \'Hello\''));

    Assert::equals("#!/bin/sh\necho 'Hello'", $block->code());
  }

  #[Test]
  public function single_backtick() {
    $this->assertTransformed(
      '<p>Use the <code>printf()</code> function</p>',
      'Use the `printf()` function'
    );
  }

  #[Test]
  public function literal_backtick_inside_code_with_multiple_backticks() {
    $this->assertTransformed(
      '<p><code>There is a literal backtick (`) here.</code></p>',
      '``There is a literal backtick (`) here.``'
    );
  }

  #[Test]
  public function single_backtick_in_a_code_span() {
    $this->assertTransformed(
      '<p><code>`</code></p>',
      '`` ` ``'
    );
  }

  #[Test]
  public function backtick_delimited_string_in_a_code_span() {
    $this->assertTransformed(
      '<p><code>`foo`</code></p>',
      '`` `foo` ``'
    );
  }

  #[Test]
  public function code_span_with_leading_space_and_no_trailing_space() {
    $this->assertTransformed(
      '<p><code>$files= [];</code></p>',
      '`` $files= [];``'
    );
  }

  #[Test]
  public function code_span_with_leading_space_and_no_trailing_space_in_sentence() {
    $this->assertTransformed(
      '<p>This <code>$files= [];</code> will initialize &quot;files&quot; to an empty array</p>',
      'This `` $files= [];`` will initialize "files" to an empty array'
    );
  }

  #[Test]
  public function html_inside_code_is_escaped() {
    $this->assertTransformed(
      '<p>Please don\'t use any <code>&lt;blink&gt;</code> tags</p>',
      'Please don\'t use any `<blink>` tags'
    );
  }

  #[Test]
  public function two_code_blocks() {
    $this->assertTransformed(
      '<p><code>&amp;#8212;</code> is the decimal-encoded equivalent of <code>&amp;mdash;</code>.</p>',
      '`&#8212;` is the decimal-encoded equivalent of `&mdash;`.'
    );
  }

  #[Test]
  public function indented_with_four_spaces() {
    $this->assertTransformed(
      '<pre><code>10 GOTO 10</code></pre>',
      '    10 GOTO 10'
    );
  }

  #[Test]
  public function indented_with_one_tab() {
    $this->assertTransformed(
      '<pre><code>10 GOTO 10</code></pre>',
      "\t10 GOTO 10"
    );
  }

  #[Test]
  public function two_lines_indented_with_four_spaces() {
    $this->assertTransformed(
      "<pre><code>10 PRINT &quot;HI&quot;\n20 GOTO 10</code></pre>",
      "    10 PRINT \"HI\"\n    20 GOTO 10"
    );
  }

  #[Test, Values(['```', '~~~'])]
  public function github_style_fenced_block($fence) {
    $this->assertTransformed(
      "<pre><code>10 PRINT &quot;HI&quot;\n20 GOTO 10</code></pre>",
      "{$fence}\n10 PRINT \"HI\"\n20 GOTO 10\n{$fence}"
    );
  }

  #[Test, Values(['```', '~~~'])]
  public function github_style_fenced_block_with_language($fence) {
    $this->assertTransformed(
      "<pre><code lang=\"basic\">10 PRINT &quot;HI&quot;\n20 GOTO 10</code></pre>",
      "{$fence}basic\n10 PRINT \"HI\"\n20 GOTO 10\n{$fence}"
    );
  }

  #[Test]
  public function code_nested_in_emphasized() {
    $this->assertTransformed(
      '<p><em><code>code</code></em></p>',
      '*`code`*'
    );
  }

  #[Test]
  public function code_nested_in_emphasized_and_strong() {
    $this->assertTransformed(
      '<p><strong><em><code>code</code></em></strong></p>',
      '***`code`***'
    );
  }

  #[Test, Values(['This is `not code', 'This is ``not code', 'This is `` not code'])]
  public function unmatched_backticks($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'net.daringfireball.markdown.Code<1 + 2>',
      (new Code('1 + 2'))->toString()
    );
  }
}