<?php namespace net\daringfireball\markdown;

abstract class Node extends \lang\Object {
  public abstract function emit($definitions);
}