<?php namespace net\daringfireball\markdown;

/**
 * Rewrites URLs 
 *
 * @see   https://tools.ietf.org/html/rfc3986#section-3.1
 * @test  xp://net.daringfireball.markdown.unittest.RewritingTest
 */
class Rewriting {
  private $pattern;
  private $images= null, $links= null, $exclude= null;

  /** @param string $pattern */
  public function __construct($pattern) {
    $this->pattern= $pattern;
  }

  /**
   * Rewrites absolute URIs
   *
   * @return self
   */
  public static function absolute() {
    return new self('~^(//|[a-z0-9+-.]+://)~i');
  }

  /**
   * Rewrites relative URIs
   *
   * @return self
   */
  public static function relative() {
    return new self('~^!(//|[a-z0-9+-.]+://)~i');
  }

  /**
   * Rewrites all URIs
   *
   * @return self
   */
  public static function all() {
    return new self('~.*~');
  }

  /**
   * Specify rewrite URL format string for images
   *
   * @param  string $format
   * @return self
   */
  public function images($format) {
    $this->images= $format;
    return $this;
  }

  /**
   * Specify rewrite URL format string for links
   *
   * @param  string $format
   * @return self
   */
  public function links($format) {
    $this->links= $format;
    return $this;
  }

  /**
   * Specify hosts to exclude. May use `*` as glob character.
   *
   * @param  string[] $hosts
   * @return self
   */
  public function exclude(array $hosts) {
    if (empty($hosts)) {
      $this->exclude= null;
    } else {
      $pattern= '';
      foreach ($hosts as $host) {
        $pattern.= '|'.strtr(preg_quote($host, '/'), ['\\*' => '.*']);
      }
      $this->exclude= '/^('.substr($pattern, 1).')$/i';
    }
    return $this;
  }

  /**
   * Rewrites the URI
   *
   * @param  string $format `sprintf`-style format string
   * @param  string $uri
   * @return string
   */
  private function rewrite($format, $uri) {
    if (preg_match($this->pattern, $uri)) {
      if (null === $this->exclude || !preg_match($this->exclude, parse_url($uri)['host'])) {
        return sprintf($format, urlencode($uri));
      }
    }
    return $uri;
  }

  /**
   * Rewrites a hrefs
   *
   * @param  string $url
   * @return string
   */
  public function href($url) {
    return $this->links ? $this->rewrite($this->links, $url) : $url;
  }

  /**
   * Rewrites img srcs
   *
   * @param  string $url
   * @return string
   */
  public function src($url) {
    return $this->images ? $this->rewrite($this->images, $url) : $url;
  }
}