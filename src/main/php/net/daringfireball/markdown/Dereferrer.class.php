<?php namespace net\daringfireball\markdown;

class Dereferrer implements Rewriting {
  private $uri;

  public function __construct($uri) {
    $this->uri= $uri;
  }

  public function rewrite($uri) {
    if (strstr($uri, '://') || 0 === strncmp($uri, '//', 2)) {
      return str_replace('{0}', urlencode($uri), $this->uri);
    } else {
      return $uri;
    }
  }
}