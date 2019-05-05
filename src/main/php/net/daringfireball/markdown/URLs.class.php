<?php namespace net\daringfireball\markdown;

class URLs {
  private $rewrite;

  public function __construct(... $rewrite) { $this->rewrite= $rewrite; }

  public function href($link) {
    $url= $link->url;
    foreach ($this->rewrite as $rewrite) {
      $url= $rewrite->href($url);
    }
    return $url;
  }

  public function src($image) {
    $url= $image->url;
    foreach ($this->rewrite as $rewrite) {
      $url= $rewrite->src($url);
    }
    return $url;
  }
}