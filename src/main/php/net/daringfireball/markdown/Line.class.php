<?php namespace net\daringfireball\markdown;

use ArrayAccess, ReturnTypeWillChange;
use lang\{IllegalStateException, Value};
use util\Objects;

class Line implements Value, ArrayAccess {
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
   * Returns the character at the current position + the given offset
   *
   * @param  int offset, defaults to 0
   * @return string
   */
  public function chr($offset= 0) {
    $i= $this->pos + $offset;
    return $i < 0 || $i >= $this->length ? null : $this->buffer[$i];
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
   * the delimiting substring from both ends, and forwards the internal 
   * pointer to the end. If none of the delimiters can be found, returns
   * NULL.
   *
   * ```php
   * $text= new Line('*Hello*');
   * $word= $text->ending('*');     // "Hello"
   * $pos= $text->pos();            // 7
   * ```
   *
   * @param  var $delimiters String for one delimiter, an array for multiple
   * @return string
   */
  public function delimited($delimiters, $offset= -1) {
    foreach ((array)$delimiters as $d) {
      $l= strlen($d);
      if (-1 === $offset) $offset= $l;

      // Find matching delimiter. A double delimiter is considered a nested delimiter
      $i= $this->pos;
      do {
        if (false === ($s= strpos($this->buffer, $d, $offset + $i))) continue 2;
        if ($d !== substr($this->buffer, $s + $l, $l)) break;
        $i= $s + $l;
      } while ($i < $this->length);

      $b= substr($this->buffer, $this->pos + $offset, $s - $this->pos - $offset);
      $this->pos= $s + $l;    // $l is correct here, not $offset
      return $b;
    }
    return null;
  }

  /**
   * Same as delimited(), but throws an exception instead of returning
   * NULL if none of the delimiters can be found 
   *
   * @param  var $delimiters String for one delimiter, an array for multiple
   * @return string
   * @throws lang.IllegalArgumentException If none of the delimiters can be found
   */
  public function ending($delimiters, $offset= -1) {
    if (null === ($chunk= $this->delimited($delimiters, $offset))) {
      throw new \lang\IllegalStateException('Unmatched '.implode(', ', (array)$delimiters));
    }
    return $chunk;
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
      if ($braces[0] === $this->buffer[$s]) $b++; else if ($braces[1] === $this->buffer[$s]) $b--;
    }
    $b= substr($this->buffer, $this->pos + 1, $s - $this->pos - 2);
    $this->pos= $s;
    return $b;
  }

  /**
   * Returns a slice of the given length starting from the given position; 
   * and forwards the internal pointer to the end.
   *
   * @param  int $length
   * @param  int $l The offset to the left (0 .. length)
   * @param  int $r The offset to the right (0 .. -length)
   * @return string
   */
  public function slice($length, $l= 0, $r= 0) {
    $b= substr($this->buffer, $this->pos + $l, $length - $l + $r);
    $this->pos+= $length;
    return $b;
  }

  /**
   * Returns an indented line
   *
   * @param  int $level
   * @return self
   */
  public function indented($level) {
    return new self((string)substr($this->buffer, $level));
  }

  /**
   * Replace all matches of a given pattern with the replacement
   *
   * @see    php://preg_replace
   * @param  string pattern
   * @param  string replacement
   */
  public function replace($pattern, $replacement) {
    $r= preg_replace($pattern, $replacement, $this->buffer);
    if (null === $r) {
      $e= new \lang\FormatException('Replacement failed');
      \xp::gc(__FILE__);
      throw $e;
    }
    $this->buffer= $r;
    $this->length= strlen($r);
  }

  /**
   * String cast overloading
   *
   * @return string
   */
  public function __toString() {
    return $this->buffer;
  }

  /**
   * isset() overloading
   *
   * @return bool
   */
  #[ReturnTypeWillChange]
  public function offsetExists($i) {
    return $i >= 0 && $i < strlen($this->buffer);
  }

  /**
   * [] read overloading
   *
   * @param  int i
   * @return string
   */
  #[ReturnTypeWillChange]
  public function offsetGet($i) {
    return ($i >= 0 && $i < strlen($this->buffer)) ? $this->buffer[$i] : null;
  }

  /**
   * [] write overloading
   *
   * @param  int i
   * @param  string value
   */
  #[ReturnTypeWillChange]
  public function offsetSet($i, $value) {
    throw new IllegalAccessException('Cannot write to line');
  }

  /**
   * unset() overloading
   *
   * @param  int i
   */
  #[ReturnTypeWillChange]
  public function offsetUnset($i) {
    throw new IllegalAccessException('Cannot write to line');
  }

  /**
   * Creates a string representation of this line
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'("'.$this->buffer.'" @ '.$this->pos.')';
  }

  /** @return string */
  public function hashCode() {
    return 'L'.Objects::hashOf([$this->buffer, $this->pos]);
  }

  /**
   * Compare
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([$this->buffer, $this->pos], [$value->buffer, $value->pos])
      : 1
    ;
  }
}