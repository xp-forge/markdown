<?php namespace net\daringfireball\markdown;

class URLs {

  /**
   * Resolve a URL
   *
   * @param  net.daringfireball.markdown.URL $url
   * @param  [:net.daringfireball.markdown.URL] $definitions
   * @return net.daringfireball.markdown.URL
   */
  public function resolve(URL $url, array $definitions) {
    if (($ref= $url->reference()) && isset($definitions[$ref])) {
      return $definitions[$ref];
    } else {
      return $url;
    }
  }
}