<?php namespace net\daringfireball\markdown;

class ListItem extends NodeList {
  public $list;

  /**
   * Creates a new list item on a given list
   *
   * @param  net.daringfireball.markdown.Listing $list
   */
  public function __construct($list) {
    $this->list= $list;
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitListItem($this, $definitions);
  }
}