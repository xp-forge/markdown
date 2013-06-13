<?php namespace net\daringfireball\markdown;

class Line extends \lang\Object implements \ArrayAccess {
  protected $buffer;
  protected $pos;
  protected $length;

  /**
   * Creates a new Line instance
   *
   * @param string $buffer
   * @param int $pos The initial offset
   */
  public function __construct($buffer, $pos= 0) {
    $this->buffer= $buffer;
    $this->pos= $pos;
    $this->length= strlen($buffer);
  }

  /**
   * Returns current position inside Line
   *
   * @return int
   */
  public function pos() {
    return $this->pos;
  }

  /**
   * Returns the buffer's length
   *
   * @return int
   */
  public function length() {
    return $this->length;
  }

  /**
   * Forward internal pointer for the current position
   *
   * @param  int $offset How many characters to forward
   * @return int The new position
   */
  public function forward($offset= 1) {
    $this->pos+= $offset;
    return $this->pos;
  }

  /**
   * Returns the character at the current position
   *
   * @return string
   */
  public function chr() {
    return $this->buffer[$this->pos];
  }

  /**
   * Returns whether the Line begins with the given string at the current offset
   *
   * @param  string $str
   * @return bool
   */
  public function matches($str) {
    return '' === $str
      ? false
      : 0 === substr_compare($this->buffer, $str, $this->pos, strlen($str))
    ;
  }

  /**
   * Finds the next occurrence of any of the given search strings
   *
   * @param  var $search A string for a single search string, an array for multiple
   * @return int The found position, or -1 if no search string is found
   */
  public function next($search) {
    foreach ((array)$search as $str) {
      $s= strpos($this->buffer, $str, $this->pos + strlen($str));
      if (false !== $s) return $s;
    }
    return -1;
  }

  /**
   * Returns the next slice of the string up until the position of any
   * of the characters in the given list of delimiters; and forwards the
   * internal pointer to the end.
   *
   * @param  string $delimiters
   * @return string
   */
  public function until($delimiters) {
    $p= strcspn($this->buffer, $delimiters, $this->pos);
    $b= substr($this->buffer, $this->pos, $p);
    $this->pos+= $p;
    return $b;
  }

  /**
   * Returns the next slice of the string up until the position if the
   * given delimiting substring, cutting of the number of characters in
   * the delimiting substring from both ends; and forwards the internal 
   * pointer to the end.
   *
   * ```php
   * $text= new Line('*Hello*');
   * $word= $text->ending('*');     // "Hello"
   * $pos= $text->pos();            // 7
   * ```
   *
   * @param  string $delimiters
   * @return string
   */
  public function ending($delimiter) {
    $l= strlen($delimiter);
    $s= strpos($this->buffer, $delimiter, $this->pos + $l);
    $b= substr($this->buffer, $this->pos + $l, $s - $this->pos - $l);
    $this->pos= $s + $l;
    return $b;
  }

  /**
   * Returns the next slice of the string with matching braces, respecting
   * nested braces; and forwards the internal pointer to the end.
   *
   * ```php
   * $text= new Line('((Hello))');
   * $word= $text->matching('()'); // "(Hello)"
   * $pos= $text->pos();           // 9
   * ```
   *
   * @param  string $delimiters
   * @return string
   */
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