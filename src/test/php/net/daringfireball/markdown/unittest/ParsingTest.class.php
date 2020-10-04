<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{BlockQuote, Cell, CodeBlock, Header, Image, Italic, Link, NodeList, Paragraph, ParseTree, Row, Table, Text};
use unittest\Test;

class ParsingTest extends MarkdownTest {

  #[Test]
  public function empty_input() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph()
      ]),
      $this->fixture->parse('')
    );
  }

  #[Test]
  public function single_paragraph() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Text('Hello World')])
      ]),
      $this->fixture->parse('Hello World')
    );
  }

  #[Test]
  public function link() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Link('url', new NodeList([new Text('link')]))])
      ]),
      $this->fixture->parse('[link](url)')
    );
  }

  #[Test]
  public function image() {
    $this->assertEquals(
      new ParseTree([
        new Paragraph([new Image('url', new NodeList([new Text('image')]))])
      ]),
      $this->fixture->parse('![image](url)')
    );
  }

  #[Test]
  public function blockquote() {
    $this->assertEquals(
      new ParseTree([
        new BlockQuote([new Text('Quote')])
      ]),
      $this->fixture->parse('> Quote')
    );
  }

  #[Test]
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

  #[Test]
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

  #[Test]
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

  #[Test]
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

  #[Test]
  public function code_block_without_language() {
    $block= new CodeBlock();
    $block->add(new Text('Code'));

    $this->assertEquals(new ParseTree([$block]), $this->fixture->parse("```\nCode\n```"));
  }

  #[Test]
  public function code_block_with_language() {
    $block= new CodeBlock('bash');
    $block->add(new Text('#!/bin/sh'));
    $block->add(new Text('echo \'Hello\''));

    $this->assertEquals(new ParseTree([$block]), $this->fixture->parse("```bash\n#!/bin/sh\necho 'Hello'\n```"));
  }
}