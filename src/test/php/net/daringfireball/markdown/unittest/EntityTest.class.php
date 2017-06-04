<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Entity;

class EntityTest extends MarkdownTest {

  #[@test]
  public function standalone_entity() {
    $this->assertTransformed('<p>&amp;</p>', '&amp;');
  }

  #[@test]
  public function entity_between_letters() {
    $this->assertTransformed('<p>AT&amp;T</p>', 'AT&amp;T');
  }

  #[@test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Entity<&amp;>',
      (new Entity('&amp;'))->toString()
    );
  }
}