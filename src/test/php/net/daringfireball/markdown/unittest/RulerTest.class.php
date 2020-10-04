<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Ruler;
use unittest\{Test, Values};

class RulerTest extends MarkdownTest {

  #[Test, Values(['* * *', '***', '*****'])]
  public function with_asterisks($input) {
    $this->assertTransformed('<hr/>', $input);
  }

  #[Test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Ruler',
      (new Ruler())->toString()
    );
  }
}