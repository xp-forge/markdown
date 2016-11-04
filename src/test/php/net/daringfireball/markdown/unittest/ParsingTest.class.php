<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\ParseTree;
use net\daringfireball\markdown\Paragraph;
use net\daringfireball\markdown\Text;
use net\daringfireball\markdown\Table;
use net\daringfireball\markdown\Row;
use net\daringfireball\markdown\Cell;

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

  #[@test]
  public function table_surrounded_by_text() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Text('A table:')]),
        new Table([
          new Row([new Cell('th', null), new Cell('th', null)]),
          new Row([new Cell('td', null, [new Text('Key')]), new Cell('td', null, [new Text('Value')])])
        ]),
        new Paragraph([new Text('That\'s it')]),
      ]),
      $this->fixture->parse("A table:\n| | |\n| - | - |\n| Key | Value |\nThat's it")
    );
  }
}