<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Text;
use test\Assert;
use test\{Test, Values};

class TextNodeTest {

  #[Test, Values(['', 'Test'])]
  public function value_passed_to_constructor($value) {
    Assert::equals($value, (new Text($value))->value);
  }

  #[Test]
  public function default_for_value_is_empty_string() {
    Assert::equals('', (new Text())->value);
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'net.daringfireball.markdown.Text<Test>',
      (new Text('Test'))->toString()
    );
  }
}