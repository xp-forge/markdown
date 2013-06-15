<?php namespace net\daringfireball\markdown\unittest;

class ListsTest extends MarkdownTest {

  #[@test, @values(array("* One\n* Two\n* Three", "- One\n- Two\n- Three", "+ One\n+ Two\n+ Three"))]
  public function unordered_list($value) {
    $this->assertTransformed(
      '<ul><li>One</li><li>Two</li><li>Three</li></ul>',
      $value
    );
  }

  #[@test]
  public function ordered_list() {
    $this->assertTransformed(
      '<ol><li>One</li><li>Two</li><li>Three</li></ol>',
      "1. One\n2. Two\n3. Three"
    );
  }

  #[@test]
  public function actual_numbers_used_have_no_effect_on_ordered_list() {
    $this->assertTransformed(
      '<ol><li>One</li><li>Two</li><li>Three</li></ol>',
      "1. One\n1. Two\n1. Three"
    );
  }

  #[@test]
  public function ordered_list_triggered_by_accident() {
    $this->assertTransformed(
      '<ol><li>What a great season.</li></ol>',
      '1986. What a great season.'
    );
  }

  #[@test]
  public function backslash_escaping_for_above_accident() {
    $this->assertTransformed(
      '<p>1986. What a great season.</p>',
      '1986\. What a great season.'
    );
  }

  #[@test]
  public function unordered_list_with_multiple_paragraphs() {
    $this->assertTransformed(
      "<ul><li><p>One</p></li><li><p>Two</p></li><li><p>Three</p></li></ul>",
      "* One\n\n* Two\n\n* Three"
    );
  }
}