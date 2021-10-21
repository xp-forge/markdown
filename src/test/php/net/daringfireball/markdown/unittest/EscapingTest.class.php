<?php namespace net\daringfireball\markdown\unittest;

use unittest\{Test, Values};

class EscapingTest extends MarkdownTest {

  #[Test, Values(['', ' ', 'Hello World'])]
  public function transforming_plain_text_equals_itself($value) {
    $this->assertTransformed('<p>'.$value.'</p>', $value);
  }

  #[Test, Values(['<', '>', '&', '"', "'"])]
  public function special_characters_are_escaped($value) {
    $this->assertTransformed('<p>'.htmlspecialchars($value, ENT_COMPAT).'</p>', $value);
  }

  #[Test, Values(['4 < 5', '6 > 5'])]
  public function escaping_inside_sentence($value) {
    $this->assertTransformed('<p>'.htmlspecialchars($value, ENT_COMPAT).'</p>', $value);
  }

  #[Test, Values(['AT&amp;T', '&quot;', '&mdash;'])]
  public function htmlentities_are_left_untouched($value) {
    $this->assertTransformed('<p>'.$value.'</p>', $value);
  }
}