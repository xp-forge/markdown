<?php namespace net\daringfireball\markdown;

/**
 * Abstract base class for all nodes and node lists
 */
abstract class Node extends \lang\Object {

  /**
   * Emit this node
   *
   * @param	 [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public abstract function emit($definitions);
}