<?php namespace net\daringfireball\markdown\unittest;

use lang\IndexOutOfBoundsException;
use net\daringfireball\markdown\{NodeList, Text};
use unittest\{Expect, Test};

class NodeListTest extends \unittest\TestCase {

  #[Test]
  public function can_create() {
    new NodeList();
  }

  #[Test]
  public function initially_empty() {
    $this->assertEquals(0, (new NodeList())->size());
  }

  #[Test]
  public function no_longer_empty_after_adding() {
    $fixture= new NodeList();
    $fixture->add(new Text('Test'));
    $this->assertEquals(1, $fixture->size());
  }

  #[Test]
  public function add_returns_added_element() {
    $text= new Text('Test');
    $this->assertEquals($text, (new NodeList())->add($text));
  }

  #[Test]
  public function get_added_element() {
    $text= new Text('Test');
    $fixture= new NodeList();
    $fixture->add($text);
    $this->assertEquals($text, $fixture->get(0));
  }

  #[Test, Expect(IndexOutOfBoundsException::class)]
  public function get_non_existant_element() {
    (new NodeList())->get(0);
  }

  #[Test]
  public function last_returns_added_element() {
    $text= new Text('Test');
    $fixture= new NodeList();
    $fixture->add($text);
    $this->assertEquals($text, $fixture->last());
  }

  #[Test]
  public function last_returns_null_when_list_is_empty() {
    $this->assertNull((new NodeList())->last());
  }

  #[Test]
  public function remove_returns_removed_element() {
    $text= new Text('Test');
    $fixture= new NodeList();
    $fixture->add($text);
    $this->assertEquals($text, $fixture->remove(0));
  }

  #[Test]
  public function remove_returns_null_when_list_is_empty() {
    $fixture= new NodeList();
    $this->assertNull($fixture->remove(0));
  }

  #[Test]
  public function remove_returns_null_when_element_does_not_exist() {
    $fixture= new NodeList();
    $fixture->add(new Text('Test'));
    $this->assertNull($fixture->remove(1));
  }
}