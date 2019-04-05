<?php namespace net\daringfireball\markdown;

/**
 * URL rewriter
 *
 * @test  xp://net.daringfireball.markdown.unittest.RewriterTest
 */
class Rewriter {
  private $uri, $exclude;

  /**
   * Creates a new dereferrer
   *
   * @param  string $uri URI of dereferrer, including `%s` placeholder
   * @param  string[] $exclude Excluded host patterns, may use `*`
   */
  public function __construct($uri, array $exclude= []) {
    $this->uri= $uri;
    if (empty($exclude)) {
      $this->exclude= null;
    } else {
      $pattern= '';
      foreach ($exclude as $host) {
        $pattern.= '|'.strtr(preg_quote($host, '/'), ['\\*' => '.*']);
      }
      $this->exclude= '/^('.substr($pattern, 1).')$/i';
    }
  }

  /**
   * Rewrites the URI
   *
   * @param  string $uri
   * @return string
   */
  public function rewrite($uri) {
    if (strstr($uri, '://') || 0 === strncmp($uri, '//', 2)) {
      if (null === $this->exclude || !preg_match($this->exclude, parse_url($uri)['host'])) {
        return sprintf($this->uri, urlencode($uri));
      }
    }
    return $uri;
  }
}