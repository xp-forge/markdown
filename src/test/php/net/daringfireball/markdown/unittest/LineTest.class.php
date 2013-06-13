<?php namespace net\daringfireball\markdown\unittest;

use net\daringfireball\markdown\Line;

class LineTest extends \unittest\TestCase {

  #[@test, @values('', 'Hello')]
  public function length_equals_length_of_buffer_passed_to_constructor($buffer) {
    $this->assertEquals(strlen($buffer), create(new Line($buffer))->length());
  }

  #[@test, @values('', 'Hello')]
  public function position_initially_defaults_to_zero($buffer) {
    $this->assertEquals(0, create(new Line($buffer))->pos());
  }

  #[@test]
  public function position_passable_as_second_constructor_parameter() {
    $this->assertEquals(2, create(new Line('buffer', 2))->pos());
  }

  #[@test]
  public function forward_position() {
    $l= new Line('Buffer');
    $l->forward(2);
    $this->assertEquals(2, $l->pos());
  }

  #[@test]
  public function forward_position_consecutively() {
    $l= new Line('Buffer');
    $l->forward(2);
    $l->forward(1);
    $this->assertEquals(3, $l->pos());
  }

  #[@test]
  public function forward_position_default_by_one() {
    $l= new Line('Buffer');
    $l->forward();
    $this->assertEquals(1, $l->pos());
  }

  #[@test]
  public function forwarding_returns_new_position() {
    $l= new Line('Buffer');
    $this->assertEquals(2, $l->forward(2));
  }

  #[@test]
  public function forwarding_consecutively_returns_new_position() {
    $l= new Line('Buffer');
    $l->forward(2);
    $this->assertEquals(3, $l->forward(1));
  }

  #[@test, @values(array(0, 1, 2, 3))]
  public function chr_returns_character_at_current_position($pos) {
    $buffer= 'Test';
    $this->assertEquals($buffer{$pos}, create(new Line($buffer, $pos))->chr());
  }
}