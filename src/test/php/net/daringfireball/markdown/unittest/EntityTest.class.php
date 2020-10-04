<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Entity;
use unittest\Test;

class EntityTest extends MarkdownTest {

  #[Test]
  public function standalone_entity() {
    $this->assertTransformed('<p>&amp;</p>', '&amp;');
  }

  #[Test]
  public function entity_between_letters() {
    $this->assertTransformed('<p>AT&amp;T</p>', 'AT&amp;T');
  }

  #[Test]
  public function string_representation() {
    $this->assertEquals(
      'net.daringfireball.markdown.Entity<&amp;>',
      (new Entity('&amp;'))->toString()
    );
  }
}