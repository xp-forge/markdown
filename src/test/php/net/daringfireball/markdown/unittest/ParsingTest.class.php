<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\ParseTree;
use net\daringfireball\markdown\Paragraph;
use net\daringfireball\markdown\Text;
use net\daringfireball\markdown\Table;
use net\daringfireball\markdown\Row;
use net\daringfireball\markdown\Cell;
use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Link;
use net\daringfireball\markdown\NodeList;

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
  public function link() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Link('url', new NodeList([new Text('link')]))])
      ]),
      $this->fixture->parse('[link](url)')
    );
  }

  #[@test]
  public function image() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Image('url', new NodeList([new Text('image')]))])
      ]),
      $this->fixture->parse('![image](url)')
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