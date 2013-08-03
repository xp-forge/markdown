<?php namespace net\daringfireball\markdown;

class BlockquoteContext extends Context {

  public function parse($lines) {
    $result= new BlockQuote();

    while ($lines->hasMoreLines()) {
      $line= new Line($lines->nextLine());
      if ('>' !== $line->chr()) break;

      $line->forward(2);
      $this->tokenize($line, $result);
    }

    return $result;
  }

  /**
   * Returns this context's name
   *
   * @return string
   */
  public function name() {
    return 'blockquote';
  }
}