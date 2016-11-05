<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Text;

class TextNodeTest extends \unittest\TestCase {

  #[@test, @values(['', 'Test'])]
  public function value_passed_to_constructor($value) {
    $this->assertEquals($value, (new Text($value))->value);
  }

  #[@test]
  public function default_for_value_is_empty_string() {
    $this->assertEquals('', (new Text())->value);
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Text<Test>',
      (new Text('Test'))->toString()
    );
  }
}