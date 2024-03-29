<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\{Image, Text};
use test\Assert;
use test\Test;

class ImagesTest extends MarkdownTest {

  #[Test]
  public function image_with_title() {
    $this->assertTransformed(
      '<p><img src="http://example.net/image.jpg" alt="This image" title="Title" /> has a title.</p>',
      '![This image](http://example.net/image.jpg "Title") has a title.'
    );
  }

  #[Test]
  public function image_without_title() {
    $this->assertTransformed(
      '<p><img src="http://example.net/image.jpg" alt="This image" /> has no title attribute.</p>',
      '![This image](http://example.net/image.jpg) has no title attribute.'
    );
  }

  #[Test]
  public function image_inside_link() {
    $this->assertTransformed(
      '<p>'.
        '<a href="http://travis-ci.org/xp-framework/xp-framework">'.
          '<img src="https://secure.travis-ci.org/xp-framework/xp-framework.png" alt="Build Status" />'.
        '</a>'.
      '</p>',
      '[![Build Status](https://secure.travis-ci.org/xp-framework/xp-framework.png)](http://travis-ci.org/xp-framework/xp-framework)'
    );
  }

  #[Test]
  public function numeric_reference() {
    $this->assertTransformed(
      '<p><img src="https://secure.travis-ci.org/xp-framework/xp-framework.png" alt="Build Status" /></p>',
      "![Build Status] [1]\n".
      "[1]: https://secure.travis-ci.org/xp-framework/xp-framework.png"
    );
  }

  #[Test]
  public function named_reference() {
    $this->assertTransformed(
      '<p><img src="https://secure.travis-ci.org/xp-framework/xp-framework.png" alt="Build Status" /></p>',
      "![Build Status] [badge]\n".
      "[badge]: https://secure.travis-ci.org/xp-framework/xp-framework.png"
    );
  }

  #[Test]
  public function standalone_exclamation_mark_not_recognized_as_image() {
    $this->assertTransformed(
      '<p>This is ! an image</p>',
      'This is ! an image'
    );
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'net.daringfireball.markdown.Image(url= http://example.com/test.gif, text= null, title= null)',
      (new Image('http://example.com/test.gif'))->toString()
    );
  }

  #[Test]
  public function string_representation_with_text() {
    $t= new Text('example');
    Assert::equals(
      'net.daringfireball.markdown.Image(url= http://example.com/test.gif, text= '.$t->toString().', title= null)',
      (new Image('http://example.com/test.gif', $t))->toString()
    );
  }

  #[Test]
  public function string_representation_with_title() {
    Assert::equals(
      'net.daringfireball.markdown.Image(url= http://example.com/test.gif, text= null, title= "Test")',
      (new Image('http://example.com/test.gif', null, 'Test'))->toString()
    );
  }
}