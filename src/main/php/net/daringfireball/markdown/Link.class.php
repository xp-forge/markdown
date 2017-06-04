<?php namespace net\daringfireball\markdown;

use util\Objects;

class Link extends Node {
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
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitLink($this, $definitions);
  }

  /** @return string */
  public function toString() {
    return sprintf(
      '%s(url= %s, text= %s, title= %s)',
      nameof($this),
      $this->url,
      Objects::stringOf($this->text),
      Objects::stringOf($this->title)
    );
  }

  /** @return string */
  public function hashCode() {
    return '#'.Objects::hashOf([$this->url, $this->text, $this->title]);
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
