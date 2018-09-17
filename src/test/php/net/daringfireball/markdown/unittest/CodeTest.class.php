<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Code;
use net\daringfireball\markdown\CodeBlock;
use net\daringfireball\markdown\Text;

class CodeTest extends MarkdownTest {

  #[@test]
  public function code_of_codeblock() {
    $block= new CodeBlock('bash');
    $block->add(new Text('#!/bin/sh'));
    $block->add(new Text('echo \'Hello\''));

    $this->assertEquals("#!/bin/sh\necho 'Hello'", $block->code());
  }

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
  public function code_span_with_leading_space_and_no_trailing_space_in_sentence() {
    $this->assertTransformed(
      '<p>This <code>$files= [];</code> will initialize &quot;files&quot; to an empty array</p>',
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

  #[@test]
  public function indented_with_four_spaces() {
    $this->assertTransformed(
      '<code>10 GOTO 10</code>',
      '    10 GOTO 10'
    );
  }

  #[@test]
  public function indented_with_one_tab() {
    $this->assertTransformed(
      '<code>10 GOTO 10</code>',
      "\t10 GOTO 10"
    );
  }

  #[@test]
  public function two_lines_indented_with_four_spaces() {
    $this->assertTransformed(
      "<code>10 PRINT &quot;HI&quot;\n20 GOTO 10</code>",
      "    10 PRINT \"HI\"\n    20 GOTO 10"
    );
  }

  #[@test]
  public function github_style_fenced_block() {
    $this->assertTransformed(
      "<code>10 PRINT &quot;HI&quot;\n20 GOTO 10</code>",
      "```\n10 PRINT \"HI\"\n20 GOTO 10\n```"
    );
  }

  #[@test]
  public function github_style_fenced_block_with_language() {
    $this->assertTransformed(
      "<code lang=\"basic\">10 PRINT &quot;HI&quot;\n20 GOTO 10</code>",
      "```basic\n10 PRINT \"HI\"\n20 GOTO 10\n```"
    );
  }

  #[@test]
  public function code_nested_in_emphasized() {
    $this->assertTransformed(
      '<p><em><code>code</code></em></p>',
      '*`code`*'
    );
  }

  #[@test]
  public function code_nested_in_emphasized_and_strong() {
    $this->assertTransformed(
      '<p><strong><em><code>code</code></em></strong></p>',
      '***`code`***'
    );
  }

  #[@test, @values([
  #  'This is `not code',
  #  'This is ``not code',
  #  'This is `` not code'
  #])]
  public function unmatched_backticks($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Code<1 + 2>',
      (new Code('1 + 2'))->toString()
    );
  }
}