<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Ruler;

class RulerTest extends MarkdownTest {

  #[@test, @values(['* * *', '***', '*****'])]
  public function with_asterisks($input) {
    $this->assertTransformed('<hr/>', $input);
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Ruler',
      (new Ruler())->toString()
    );
  }
}