<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Text;
use unittest\{Test, Values};

class TextNodeTest extends \unittest\TestCase {

  #[Test, Values(['', 'Test'])]
  public function value_passed_to_constructor($value) {
    $this->assertEquals($value, (new Text($value))->value);
  }

  #[Test]
  public function default_for_value_is_empty_string() {
    $this->assertEquals('', (new Text())->value);
  }

  #[Test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Text<Test>',
      (new Text('Test'))->toString()
    );
  }
}