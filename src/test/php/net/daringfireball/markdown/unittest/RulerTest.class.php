<?php namespace net\daringfireball\markdown\unittest;

class RulerTest extends MarkdownTest {

  #[@test, @values(array('* * *', '***', '*****'))]
  public function with_asterisks($input) {
    $this->assertTransformed('<hr/>', $input);
  }
}