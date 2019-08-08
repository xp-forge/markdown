<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\BlockQuote;
use net\daringfireball\markdown\Cell;
use net\daringfireball\markdown\CodeBlock;
use net\daringfireball\markdown\Header;
use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Italic;
use net\daringfireball\markdown\Link;
use net\daringfireball\markdown\NodeList;
use net\daringfireball\markdown\Paragraph;
use net\daringfireball\markdown\ParseTree;
use net\daringfireball\markdown\Row;
use net\daringfireball\markdown\Table;
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
  public function blockquote() {
    $this->assertEquals(
      new ParseTree([
        new BlockQuote([new Text('Quote')])
      ]),
      $this->fixture->parse('> Quote')
    );
  }

  #[@test]
  public function nested_blockquote() {
    $this->assertEquals(
      new ParseTree([
        new BlockQuote([
          new BlockQuote([new Text('Quote')])
        ])
      ]),
      $this->fixture->parse('> > Quote')
    );
  }

  #[@test]
  public function nested_blockquotes() {
    $this->assertEquals(
      new ParseTree([
        new BlockQuote([
          new BlockQuote([new Text('Second')]),
          new Text('First')
        ])
      ]),
      $this->fixture->parse("> > Second\n> First\n")
    );
  }

  #[@test]
  public function blockquote_with_formatting() {
    $this->assertEquals(
      new ParseTree([
        new BlockQuote([
          new Header(1, [new Text('Quote')]),
          new Text('Next '),
          new Italic([new Text('line')])
        ])
      ]),
      $this->fixture->parse("> # Quote\n> Next _line_\n")
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

  #[@test]
  public function code_block_without_language() {
    $block= new CodeBlock();
    $block->add(new Text('Code'));

    $this->assertEquals(new ParseTree([$block]), $this->fixture->parse("```\nCode\n```"));
  }

  #[@test]
  public function code_block_with_language() {
    $block= new CodeBlock('bash');
    $block->add(new Text('#!/bin/sh'));
    $block->add(new Text('echo \'Hello\''));

    $this->assertEquals(new ParseTree([$block]), $this->fixture->parse("```bash\n#!/bin/sh\necho 'Hello'\n```"));
  }
}