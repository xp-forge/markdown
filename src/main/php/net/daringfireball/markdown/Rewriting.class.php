<?php namespace net\daringfireball\markdown;

interface Rewriting {

  /**
   * Rewrites a given URI
   *
   * @param  string $uri
   * @return string
   */
  public function rewrite($uri);
}