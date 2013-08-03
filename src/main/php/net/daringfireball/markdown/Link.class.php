<?php namespace net\daringfireball\markdown;

class Link extends Node {
  public function __construct($url, $text= null, $title= null) {
    $this->url= $url;
    $this->text= $text;
    $this->title= $title;
  }

  public function toString() {
    return sprintf(
      '%s(url= %s, text= %s, title= %s)',
      $this->getClassName(),
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
    $attr= $link->title ? ' title="'.htmlspecialchars($link->title).'"' : '';
    $text= $this->text ? $this->text->emit($definitions) : $link->url;
    return '<a href="'.htmlspecialchars($link->url).'"'.$attr.'>'.$text.'</a>';
  }
}