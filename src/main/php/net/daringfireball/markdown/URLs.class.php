<?php namespace net\daringfireball\markdown;

class URLs {
  private $rewrite= [];

  public function derefer($uri) {
    $this->rewrite[Link::class]= $uri;
    return $this;
  }

  /**
   * Resolve a URL
   *
   * @param  net.daringfireball.markdown.URL $url
   * @param  [:net.daringfireball.markdown.URL] $definitions
   * @return net.daringfireball.markdown.URL
   */
  public function resolve(URL $url, array $definitions) {
    if (($ref= $url->reference()) && isset($definitions[$ref])) {
      $target= $definitions[$ref];
    } else {
      $target= $url;
    }

    $kind= get_class($url);
    if (!isset($this->rewrite[$kind])) return $target;

    // Pass links through dereferrer
    $deref= clone $target;
    $deref->url= str_replace('{0}', urlencode($target->url), $this->rewrite[$kind]);
    return $deref;
  }
}