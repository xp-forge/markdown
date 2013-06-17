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
  public function unordered_list_with_paragraphs() {
    $this->assertTransformed(
      "<ul><li><p>One</p></li><li><p>Two</p></li><li><p>Three</p></li></ul>",
      "* One\n\n* Two\n\n* Three"
    );
  }

  #[@test]
  public function unordered_list_with_multiple_paragraphs() {
    $this->assertTransformed(
      "<ul><li><p>One</p><p>One and a half</p></li><li><p>Two</p></li></ul>",
      "* One\n\n  One and a half\n\n* Two"
    );
  }

  #[@test]
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

  #[@test]
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

  #[@test, @ignore('Not yet implemented')]
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
}