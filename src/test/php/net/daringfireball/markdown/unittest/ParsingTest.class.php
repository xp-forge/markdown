<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\ParseTree;
use net\daringfireball\markdown\Paragraph;
use net\daringfireball\markdown\Text;

class ParsingTest extends MarkdownTest {

  #[@test]
  public function empty_input() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph()
      ]),
      $this->fixture->parse('')
    );
  }

  #[@test]
  public function single_paragraph() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Text('Hello World')])
      ]),
      $this->fixture->parse('Hello World')
    );
  }
}