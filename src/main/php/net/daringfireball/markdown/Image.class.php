<?php namespace net\daringfireball\markdown;

class Image extends Node {
  public function __construct($url, $text= null, $title= null) {
    $this->url= $url;
    $this->text= $text;
    $this->title= $title;
  }

  public function toString() {
    return sprintf(
      '%s(url= %s, text= %s, title= %s)',
      nameof($this),
      $this->url,
      \xp::stringOf($this->text),
      \xp::stringOf($this->title)
    );
  }

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
}