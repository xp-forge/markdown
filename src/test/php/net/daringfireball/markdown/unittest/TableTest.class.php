<?php namespace net\daringfireball\markdown\unittest;

class TableTest extends MarkdownTest {

  #[@test]
  public function table() {
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
}