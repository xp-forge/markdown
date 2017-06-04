<?php namespace net\daringfireball\markdown\unittest;

use lang\IndexOutOfBoundsException;
use net\daringfireball\markdown\NodeList;
use net\daringfireball\markdown\Text;

class NodeListTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new NodeList();
  }

  #[@test]
  public function initially_empty() {
    $this->assertEquals(0, (new NodeList())->size());
  }

  #[@test]
  public function no_longer_empty_after_adding() {
    $fixture= new NodeList();
    $fixture->add(new Text('Test'));
    $this->assertEquals(1, $fixture->size());
  }

  #[@test]
  public function add_returns_added_element() {
    $text= new Text('Test');
    $this->assertEquals($text, (new NodeList())->add($text));
  }

  #[@test]
  public function get_added_element() {
    $text= new Text('Test');
    $fixture= new NodeList();
    $fixture->add($text);
    $this->assertEquals($text, $fixture->get(0));
  }

  #[@test, @expect(IndexOutOfBoundsException::class)]
  public function get_non_existant_element() {
    (new NodeList())->get(0);
  }

  #[@test]
  public function last_returns_added_element() {
    $text= new Text('Test');
    $fixture= new NodeList();
    $fixture->add($text);
    $this->assertEquals($text, $fixture->last());
  }

  #[@test]
  public function last_returns_null_when_list_is_empty() {
    $this->assertNull((new NodeList())->last());
  }

  #[@test]
  public function remove_returns_removed_element() {
    $text= new Text('Test');
    $fixture= new NodeList();
    $fixture->add($text);
    $this->assertEquals($text, $fixture->remove(0));
  }

  #[@test]
  public function remove_returns_null_when_list_is_empty() {
    $fixture= new NodeList();
    $this->assertNull($fixture->remove(0));
  }

  #[@test]
  public function remove_returns_null_when_element_does_not_exist() {
    $fixture= new NodeList();
    $fixture->add(new Text('Test'));
    $this->assertNull($fixture->remove(1));
  }
}