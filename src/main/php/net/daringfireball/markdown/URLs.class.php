<?php namespace net\daringfireball\markdown;

class URLs {
  private $rewriting= [];

  /**
   * Rewrites links
   *
   * @param  net.daringfireball.markdown.Rewriting
   * @return self
   */
  public function rewriting($rewrite) {
    $this->rewriting[Link::class]= $rewrite;
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
    if (!isset($this->rewriting[$kind])) return $target;

    $rewritten= clone $target;
    $rewritten->url= $this->rewriting[$kind]->rewrite($target->url);
    return $rewritten;
  }
}