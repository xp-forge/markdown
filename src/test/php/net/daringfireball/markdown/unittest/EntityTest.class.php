<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Entity;
use test\{Assert, Test, Values};

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
  public function uppercase_entity() {
    $this->assertTransformed('<p>&Aacute;</p>', '&Aacute;');
  }

  #[Test]
  public function numeric_entity() {
    $this->assertTransformed('<p>&#60;</p>', '&#60;');
  }

  #[Test]
  public function hex_entity() {
    $this->assertTransformed('<p>It&#x39;s a wrap</p>', 'It&#x39;s a wrap');
  }

  #[Test]
  public function string_representation() {
    Assert::equals(
      'net.daringfireball.markdown.Entity<&amp;>',
      (new Entity('&amp;'))->toString()
    );
  }

  #[Test, Values(['&', '1&2', 'Hello & World', '&amp is not a entity;'])]
  public function unescaped_ampersands_not_confused_with_entity($input) {
    $this->assertTransformed('<p>'.htmlspecialchars($input).'</p>', $input);
  }
}