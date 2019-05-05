<?php namespace net\daringfireball\markdown;

class Rewriting {
  private $pattern;
  private $images= null, $links= null, $exclude= null;

  public function __construct($pattern) {
    $this->pattern= $pattern;
  }

  /**
   * Rewrites absolute URIs
   *
   * @see    https://tools.ietf.org/html/rfc3986#section-3.1
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

  public function images($images) {
    $this->images= $images;
    return $this;
  }

  public function links($links) {
    $this->links= $links;
    return $this;
  }

  public function exclude($exclude) {
    if (empty($exclude)) {
      $this->exclude= null;
    } else {
      $pattern= '';
      foreach ($exclude as $host) {
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
  public function rewrite($format, $uri) {
    if (preg_match($this->pattern, $uri)) {
      if (null === $this->exclude || !preg_match($this->exclude, parse_url($uri)['host'])) {
        return sprintf($format, urlencode($uri));
      }
    }
    return $uri;
  }

  public function href($url) {
    return $this->links ? $this->rewrite($this->links, $url) : $url;
  }

  public function src($url) {
    return $this->images ? $this->rewrite($this->images, $url) : $url;
  }
}