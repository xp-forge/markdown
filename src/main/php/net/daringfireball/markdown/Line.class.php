<?php namespace net\daringfireball\markdown;

class Line extends \lang\Object implements \ArrayAccess {
  protected $buffer;
  protected $pos;
  protected $length;

  public function __construct($buffer, $pos= 0) {
    $this->buffer= $buffer;
    $this->pos= $pos;
    $this->length= strlen($buffer);
  }

  public function matches($str) {
    return 0 === substr_compare($this->buffer, $str, $this->pos, strlen($str));
  }

  public function until($delimiter) {
    $l= strlen($delimiter);
    $s= strpos($this->buffer, $delimiter, $this->pos + $l);
    $b= substr($this->buffer, $this->pos + $l, $s - $this->pos - $l);
    $this->pos= $s + $l;
    return $b;
  }

  public function matching($braces) {
    for (
      $b= 1, $s= $this->pos, $e= 1, $l= strlen($this->buffer);
      $b && (($s+= $e) < $l);
      $s++, $e= strcspn($this->buffer, $braces, $s)
    ) {
      if ($braces{0} === $this->buffer{$s}) $b++; else if ($braces{1} === $this->buffer{$s}) $b--;
    }
    $b= substr($this->buffer, $this->pos + 1, $s - $this->pos - 2);
    $this->pos= $s;
    return $b;
  }

  public function slice($length, $l= 0) {
    $b= substr($this->buffer, $this->pos + $l, $length - $l);
    $this->pos+= $length + $l;
    return $b;
  }

  public function next($search) {
    foreach ((array)$search as $str) {
      $s= strpos($this->buffer, $str, $this->pos + strlen($str));
      if (false !== $s) return $s;
    }
    return -1;
  }

  public function forward($offset= 1) {
    $this->pos+= $offset;
    return $this->pos;
  }

  public function chr() {
    return $this->buffer[$this->pos];
  }

  public function pos() {
    return $this->pos;
  }

  public function length() {
    return $this->length;
  }

  public function __toString() {
    return $this->buffer;
  }

  public function offsetExists($i) {
    return $i > 0 && $i < strlen($this->buffer);
  }

  public function offsetGet($i) {
    return $this->buffer[$i];
  }

  public function offsetSet($i, $value) {
    throw new IllegalAccessException('Cannot write to line');
  }

  public function offsetUnset($i) {
    throw new IllegalAccessException('Cannot write to line');
  }
}