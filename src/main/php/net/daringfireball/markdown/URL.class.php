<?php namespace net\daringfireball\markdown;

use util\Objects;

abstract class URL extends Node {
  public $url, $text, $title;

  /**
   * Creates a new image element
   *
   * @param  string $url
   * @param  net.daringfireball.markdown.Node $text Optional text
   * @param  string $title Optional title
   */
  public function __construct($url, Node $text= null, $title= null) {
    $this->url= $url;
    $this->text= $text;
    $this->title= $title;
  }

  /**
   * Resolves this URL against given definitions if this URL starts with an `@`;
   * returning this URL itself instead.
   *
   * @param  [:self] $definitions
   * @return self
   */
  public function resolve($definitions) {
    return '@' === $this->url{0} && isset($definitions[$ref= substr($this->url, 1)])
      ? $definitions[$ref]
      : $this
    ;
  }

  /** @return string */
  public function toString() {
    return sprintf(
      '%s(url= %s, text= %s, title= %s)',
      nameof($this),
      $this->url,
      null === $this->text ? 'null' : $this->text->toString(),
      null === $this->title ? 'null' : '"'.$this->title.'"'
    );
  }

  /** @return string */
  public function hashCode() {
    return Objects::hashOf([static::class, $this->url, $this->text, $this->title]);
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $value
   * @return string
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare(
        [$this->url, $this->title, $this->text],
        [$value->url, $value->title, $value->text]
      )
      : 1
    ;
  }
}