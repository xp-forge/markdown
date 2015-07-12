<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Text;

/**
 * Tests the "Text" node
 */
class TextNodeTest extends \unittest\TestCase {

  #[@test, @values(array('', 'Test'))]
  public function value_passed_to_constructor($value) {
    $this->assertEquals($value, (new Text($value))->value);
  }

  #[@test]
  public function default_for_value_is_empty_string() {
    $this->assertEquals('', (new Text())->value);
  }

  #[@test]
  public function special_chars_are_escaped() {
    $this->assertEquals('4 &lt; 5', (new Text('4 < 5'))->emit(array()));
  }

  #[@test]
  public function one_trailing_space() {
    $this->assertEquals('Test ', (new Text('Test '))->emit(array()));
  }

  #[@test, @values(array('  ', '   '))]
  public function manual_line_break_with_two_or_more_spaces($spaces) {
    $this->assertEquals('Test<br/>', (new Text('Test'.$spaces))->emit(array()));
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Text<Test>',
      (new Text('Test'))->toString()
    );
  }
}