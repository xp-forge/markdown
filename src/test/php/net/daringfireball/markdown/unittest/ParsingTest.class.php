<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{BlockQuote, Cell, CodeBlock, Header, Image, Italic, Link, NodeList, Paragraph, ParseTree, Row, Table, Text};
use test\Assert;
use test\Test;

class ParsingTest extends MarkdownTest {

  #[Test]
  public function empty_input() {
    Assert::equals(
      new ParseTree([
        new Paragraph()
      ]),
      $this->fixture->parse('')
    );
  }

  #[Test]
  public function single_paragraph() {
    Assert::equals(
      new ParseTree([
        new Paragraph([new Text('Hello World')])
      ]),
      $this->fixture->parse('Hello World')
    );
  }

  #[Test]
  public function link() {
    Assert::equals(
      new ParseTree([
        new Paragraph([new Link('url', new NodeList([new Text('link')]))])
      ]),
      $this->fixture->parse('[link](url)')
    );
  }

  #[Test]
  public function image() {
    Assert::equals(
      new ParseTree([
        new Paragraph([new Image('url', new NodeList([new Text('image')]))])
      ]),
      $this->fixture->parse('![image](url)')
    );
  }

  #[Test]
  public function blockquote() {
    Assert::equals(
      new ParseTree([
        new BlockQuote([new Text('Quote')])
      ]),
      $this->fixture->parse('> Quote')
    );
  }

  #[Test]
  public function nested_blockquote() {
    Assert::equals(
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
    Assert::equals(
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
    Assert::equals(
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
    Assert::equals(
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

    Assert::equals(new ParseTree([$block]), $this->fixture->parse("```\nCode\n```"));
  }

  #[Test]
  public function code_block_with_language() {
    $block= new CodeBlock('bash');
    $block->add(new Text('#!/bin/sh'));
    $block->add(new Text('echo \'Hello\''));

    Assert::equals(new ParseTree([$block]), $this->fixture->parse("```bash\n#!/bin/sh\necho 'Hello'\n```"));
  }
}