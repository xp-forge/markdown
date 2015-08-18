<?php namespace net\daringfireball\markdown\unittest;

class TableTest extends MarkdownTest {

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
}