<?php namespace net\daringfireball\markdown\unittest;

class ImagesTest extends MarkdownTest {

  #[@test]
  public function image_with_title() {
    $this->assertTransformed(
      '<p><img src="http://example.net/image.jpg" alt="This image" title="Title"/> has a title.</p>',
      '![This image](http://example.net/image.jpg "Title") has a title.'
    );
  }

  #[@test]
  public function image_without_title() {
    $this->assertTransformed(
      '<p><img src="http://example.net/image.jpg" alt="This image"/> has no title attribute.</p>',
      '![This image](http://example.net/image.jpg) has no title attribute.'
    );
  }

  #[@test, @ignore('Does not work yet; requires nesting inside handlers')]
  public function image_inside_link() {
    $this->assertTransformed(
      '<p>'.
        '<a href="http://travis-ci.org/xp-framework/xp-framework">'.
          '<img src="https://secure.travis-ci.org/xp-framework/xp-framework.png" alt="Build Status"/>'.
        '</a>'.
      '</p>',
      '[![Build Status](https://secure.travis-ci.org/xp-framework/xp-framework.png)](http://travis-ci.org/xp-framework/xp-framework)'
    );
  }
}