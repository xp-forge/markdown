<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Cell;
use net\daringfireball\markdown\Row;
use net\daringfireball\markdown\Table;
use net\daringfireball\markdown\Text;

class TableTest extends MarkdownTest {

  #[@test]
  public function string_representation_of_table() {
    $this->assertEquals(
      "net.daringfireball.markdown.Table@{\n".
      "  net.daringfireball.markdown.Row@{\n".
      "    net.daringfireball.markdown.Cell(type= th, alignment= )@[net.daringfireball.markdown.Text<Header>]\n".
      "  }\n".
      "}",
      (new Table([new Row([new Cell('th', null, [new Text('Header')])])]))->toString()
    );
  }

  #[@test]
  public function rows_of_table() {
    $rows= [
      new Row([new Cell('th', null), new Cell('th', null)]),
      new Row([new Cell('td', null, [new Text('Key')]), new Cell('td', null, [new Text('Value')])])
    ];
    $this->assertEquals($rows, (new Table($rows))->rows());
  }

  #[@test]
  public function cells_of_row() {
    $cells= [new Cell('th', null), new Cell('th', null)];
    $this->assertEquals($cells, (new Row($cells))->cells());
  }

  #[@test]
  public function simple_layout() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td>T-Shirt</td><td>12.49</td></tr>'.
      '<tr><td>Server</td><td>99.99</td></tr>'.
      '</table>',
      "Product | Price\n".
      "------- | -----\n".
      "T-Shirt | 12.49\n".
      "Server  | 99.99\n"
    );
  }

  #[@test]
  public function not_simple_layout() {
    $this->assertTransformed(
      "<p>Product | Price\nSecond Line</p>",
      "Product | Price\n".
      "Second Line"
    );
  }

  #[@test]
  public function wrapped_layout() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td>T-Shirt</td><td>12.49</td></tr>'.
      '<tr><td>Server</td><td>99.99</td></tr>'.
      '</table>',
      "| Product | Price |\n".
      "| ------- | ----- |\n".
      "| T-Shirt | 12.49 |\n".
      "| Server  | 99.99 |\n"
    );
  }

  #[@test]
  public function not_wrapped_layout() {
    $this->assertTransformed(
      "<p>| Product | Price |\nSecond Line</p>",
      "| Product | Price |\n".
      "Second Line"
    );
  }

  #[@test]
  public function compact_layout() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td>T-Shirt</td><td>12.49</td></tr>'.
      '<tr><td>Server</td><td>99.99</td></tr>'.
      '</table>',
      "|Product|Price|\n".
      "|-------|-----|\n".
      "|T-Shirt|12.49|\n".
      "|Server |99.99|\n"
    );
  }

  #[@test]
  public function cells_may_contain_markup() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td><a href="https://t-shirt.example.com/">T-Shirt</a></td><td><em>$12.49</em></td></tr>'.
      '</table>',
      "| Product | Price |\n".
      "| ------- | ----- |\n".
      "| [T-Shirt](https://t-shirt.example.com/) | *$12.49* |\n"
    );
  }

  #[@test]
  public function cells_may_be_empty() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td><a href="https://t-shirt.example.com/">T-Shirt</a></td><td></td></tr>'.
      '</table>',
      "| Product | Price |\n".
      "| ------- | ----- |\n".
      "| [T-Shirt](https://t-shirt.example.com/) | |\n"
    );
  }

  #[@test]
  public function empty_header_cells() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th></th><th></th></tr>'.
      '<tr><td><a href="https://t-shirt.example.com/">T-Shirt</a></td><td><em>$12.49</em></td></tr>'.
      '</table>',
      "| | |\n".
      "| - | - |\n".
      "| [T-Shirt](https://t-shirt.example.com/) | *$12.49* |\n"
    );
  }

  #[@test]
  public function alignment() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th style="text-align: left">Product</th><th style="text-align: center">Size</th><th style="text-align: right">Price</th></tr>'.
      '<tr><td style="text-align: left">T-Shirt</td><td style="text-align: center">S</td><td style="text-align: right">12.49</td></tr>'.
      '</table>',
      "| Product | Size | Price |\n".
      "|:--------|:----:|------:|\n".
      "| T-Shirt |   S  | 12.49 |\n"
    );
  }

  #[@test]
  public function line_after_table() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td>T-Shirt</td><td>12.49</td></tr>'.
      '</table>'.
      '<p>Line</p>',
      "Product | Price\n".
      "------- | -----\n".
      "T-Shirt | 12.49\n".
      "\n".
      "Line"
    );
  }

  #[@test]
  public function extra_column() {
    $this->assertTransformed(
      '<table>'.
      '<tr><th>Product</th><th>Price</th></tr>'.
      '<tr><td>T-Shirt</td><td>12.49</td><td>(out of stock)</td></tr>'.
      '</table>'.
      '<p>Line</p>',
      "Product | Price\n".
      "------- | -----\n".
      "T-Shirt | 12.49 | (out of stock)\n".
      "\n".
      "Line"
    );
  }

  #[@test, @values([
  #  ['TLDR: docker (system|container|volume|image) prune'],
  #  ['Bei Shell-Escape ; und | wegzulassen wäre aber auch gewagt'],
  #  ['For instance, | did not work...'],
  #  ['PDF | Powerpoint'],
  #])]
  public function issue_12($input) {
    $this->assertTransformed('<p>'.$input.'</p>', $input);
  }
}