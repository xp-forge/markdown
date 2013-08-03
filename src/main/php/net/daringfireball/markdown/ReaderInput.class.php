<?php namespace net\daringfireball\markdown;

class ReaderInput extends Input {

  public function __construct($reader) {
    $this->reader= $reader;
  }

  protected function readLine() {
    return $this->reader->readLine();
  }
}