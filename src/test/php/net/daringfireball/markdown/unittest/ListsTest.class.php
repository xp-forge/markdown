<?php namespace net\daringfireball\markdown\unittest;

use unittest\{Test, Values};

class ListsTest extends MarkdownTest {

  #[Test, Values(["* One\n* Two\n* Three", "- One\n- Two\n- Three", "+ One\n+ Two\n+ Three"])]
  public function unordered_list($value) {
    $this->assertTransformed(
      '<ul><li>One</li><li>Two</li><li>Three</li></ul>',
      $value
    );
  }

  #[Test]
  public function ordered_list() {
    $this->assertTransformed(
      '<ol><li>One</li><li>Two</li><li>Three</li></ol>',
      "1. One\n2. Two\n3. Three"
    );
  }

  #[Test]
  public function actual_numbers_used_have_no_effect_on_ordered_list() {
    $this->assertTransformed(
      '<ol><li>One</li><li>Two</li><li>Three</li></ol>',
      "1. One\n1. Two\n1. Three"
    );
  }

  #[Test]
  public function ordered_list_triggered_by_accident() {
    $this->assertTransformed(
      '<ol><li>What a great season.</li></ol>',
      '1986. What a great season.'
    );
  }

  #[Test]
  public function backslash_escaping_for_above_accident() {
    $this->assertTransformed(
      '<p>1986. What a great season.</p>',
      '1986\. What a great season.'
    );
  }

  #[Test]
  public function unordered_list_with_paragraphs() {
    $this->assertTransformed(
      "<ul><li><p>One</p></li><li><p>Two</p></li><li><p>Three</p></li></ul>",
      "* One\n\n* Two\n\n* Three"
    );
  }

  #[Test]
  public function unordered_list_with_multiple_paragraphs() {
    $this->assertTransformed(
      "<ul><li><p>One</p><p>One and a half</p></li><li><p>Two</p></li></ul>",
      "* One\n\n  One and a half\n\n* Two"
    );
  }

  #[Test]
  public function list_without_paragraphs_and_one_with() {
    $this->assertTransformed(
      "<ul><li>One</li><li>Two</li></ul>".
      "<p>Between</p>".
      "<ul><li><p>A</p></li><li><p>B</p></li></ul>".
      "<p>After</p>",
      "* One\n* Two\n\n".
      "Between\n".
      "* A\n\n* B\n\n".
      "After"
    );
  }

  #[Test]
  public function list_with_paragraphs_and_one_without() {
    $this->assertTransformed(
      "<ul><li><p>A</p></li><li><p>B</p></li></ul>".
      "<p>Between</p>".
      "<ul><li>One</li><li>Two</li></ul>".
      "<p>After</p>",
      "* A\n\n* B\n\n".
      "Between\n".
      "* One\n* Two\n\n".
      "After"
    );
  }

  #[Test]
  public function sublist() {
    $this->assertTransformed(
      "<ul>".
        "<li>One</li>".
        "<li>Two".
          "<ul><li>Two A</li><li>Two B</li></ul>".
        "</li>".
        "<li>Three</li>".
      "</ul>",
      "* One\n".
      "* Two\n".
      "  * Two A\n".
      "  * Two B\n".
      "* Three\n"
    );
  }

  #[Test, Values(["", "\n", "\n\n"])]
  public function list_followed_by_ruler($spacing) {
    $this->assertTransformed(
      "<ul>".
        "<li>One</li>".
        "<li>Two</li>".
        "<li>Three</li>".
      "</ul>".
      "<hr/>",
      "* One\n".
      "* Two\n".
      "* Three\n".
      $spacing.
      "* * *"
    );
  }
}