<?php namespace net\daringfireball\markdown;

class URLs {

  public function href($link) { return $link->url; }

  public function src($image) { return $image->url; }
}