<?php namespace net\daringfireball\markdown\unittest;

class EscapingTest extends MarkdownTest {

  #[@test, @values(array('', ' ', 'Hello World'))]
  public function transforming_plain_text_equals_itself($value) {
    $this->assertTransformed('<p>'.$value.'</p>', $value);
  }

  #[@test, @values(array('<', '>', '&', '"', "'"))]
  public function special_characters_are_escaped($value) {
    $this->assertTransformed('<p>'.htmlspecialchars($value).'</p>', $value);
  }

  #[@test, @values(array('4 < 5', '6 > 5'))]
  public function escaping_inside_sentence($value) {
    $this->assertTransformed('<p>'.htmlspecialchars($value).'</p>', $value);
  }

  #[@test, @values(array('AT&amp;T', '&quot;', '&mdash;'))]
  public function htmlentities_are_left_untouched($value) {
    $this->assertTransformed('<p>'.$value.'</p>', $value);
  }
}