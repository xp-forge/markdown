<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Image;
use net\daringfireball\markdown\Text;

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

  #[@test]
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

  #[@test]
  public function standalone_exclamation_mark_not_recognized_as_image() {
    $this->assertTransformed(
      '<p>This is ! an image</p>',
      'This is ! an image'
    );
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Image(url= http://example.com/test.gif, text= null, title= null)',
      (new Image('http://example.com/test.gif'))->toString()
    );
  }

  #[@test]
  public function string_representation_with_text() {
    $t= new Text('example');
    $this->assertEquals(
      'net.daringfireball.markdown.Image(url= http://example.com/test.gif, text= '.$t->toString().', title= null)',
      (new Image('http://example.com/test.gif', $t))->toString()
    );
  }

  #[@test]
  public function string_representation_with_title() {
    $this->assertEquals(
      'net.daringfireball.markdown.Image(url= http://example.com/test.gif, text= null, title= "Test")',
      (new Image('http://example.com/test.gif', null, 'Test'))->toString()
    );
  }
}