<?php namespace net\daringfireball\markdown;

class Listing extends NodeList {
  public $type, $paragraphs;

  /**
   * Creates a new list
   *
   * @param  string $type either "ul" or "ol"
   * @param  bool $paragraphs Whether to use paragraphs inside list items.
   */
  public function __construct($type, $paragraphs= false) {
    $this->type= $type;
    $this->paragraphs= $paragraphs;
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return sprintf(
      '%s(%s%s)<%s>',
      nameof($this),
      $this->type,
      $this->paragraphs ? ' using paragraphs' : '',
      $this->nodesIndented('  ')
    );
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitListing($this, $definitions);
  }
}