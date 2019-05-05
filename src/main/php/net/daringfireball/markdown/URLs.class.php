<?php namespace net\daringfireball\markdown;

/**
 * URL handling for `a href` and `img src` in HTML emitter.
 *
 * @test  xp://net.daringfireball.markdown.unittest.URLsTest
 */
class URLs {
  private $rewrite;

  /** @param net.daringfireball.markdown.Rewriting... $rewrite */
  public function __construct(... $rewrite) { $this->rewrite= $rewrite; }

  /**
   * Create `href` attribute for links
   *
   * @param  net.daringfireball.markdown.Link $link
   * @return string
   */
  public function href($link) {
    $url= $link->url;
    foreach ($this->rewrite as $rewrite) {
      $url= $rewrite->href($url);
    }
    return $url;
  }

  /**
   * Create `src` attribute for images
   *
   * @param  net.daringfireball.markdown.Image $image
   * @return string
   */
  public function src($image) {
    $url= $image->url;
    foreach ($this->rewrite as $rewrite) {
      $url= $rewrite->src($url);
    }
    return $url;
  }
}