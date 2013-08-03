<?php namespace net\daringfireball\markdown;

class StringInput extends Input {
  protected $str= '';
  protected $offset= 0;

  public function __construct($str) {
    $this->str= $str;
    $this->offset= 0;
  }

  protected function readLine() {
    if ($this->offset >= strlen($this->str)) return null;
    $c= strcspn($this->str, "\n", $this->offset);
    $line= substr($this->str, $this->offset, $c);
    $this->offset+= $c + 1;
    return $line;
  }
}