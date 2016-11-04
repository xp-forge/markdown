<?php namespace net\daringfireball\markdown;

use util\Objects;

class Image extends Node {
  private $url, $text, $title;

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
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return sprintf(
      '%s(url= %s, text= %s, title= %s)',
      nameof($this),
      $this->url,
      \xp::stringOf($this->text),
      \xp::stringOf($this->title)
    );
  }

  /**
   * Emit this text node
   *
   * @param  [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    if ('@' === $this->url{0}) {
      $link= $definitions[substr($this->url, 1)];
    } else {
      $link= $this;
    }
    $attr= '';
    $this->text && $attr.= ' alt="'.$this->text->emit($definitions).'"';
    $link->title && $attr.= ' title="'.htmlspecialchars($link->title).'"';
    return '<img src="'.htmlspecialchars($link->url).'"'.$attr.'/>';
  }

  /**
   * Returns whether a given comparison value is equal to this node list
   *
   * @param  var $cmp
   * @return string
   */
  public function equals($cmp) {
    return (
      $cmp instanceof self &&
      $this->url === $cmp->url &&
      $this->title === $cmp->title &&
      Objects::equal($this->text, $cmp->text)
    );
  }
}