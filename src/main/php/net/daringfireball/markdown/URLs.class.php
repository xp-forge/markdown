<?php namespace net\daringfireball\markdown;

class URLs {
  private $rewriting= [];

  /**
   * Rewrites links
   *
   * @param  string|lang.XPClass $type
   * @param  net.daringfireball.markdown.Rewriting $rewrite
   * @return self
   */
  public function rewriting($type, $rewrite) {
    $this->rewriting[$type instanceof XPClass ? $type->literal() : $type]= $rewrite;
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

    foreach ($this->rewriting as $type => $rewrite) {
      if ($url instanceof $type) {
        $rewritten= clone $target;
        $rewritten->url= $rewrite->rewrite($target->url);
        return $rewritten;
      }
    }
    return $target;
  }
}