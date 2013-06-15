<?php namespace net\daringfireball\markdown;

class Listing extends NodeList {
  public $type;
  public $paragraphs;

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
      $this->getClassName(),
      $this->type,
      $this->paragraphs ? ' using paragraphs' : '',
      \xp::stringOf($this->nodes)
    );
  }

  /**
   * Emit this text node
   *
   * @param  [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    foreach ($this->nodes as $item) {
      $item->paragraphs= $this->paragraphs;
      $r.= $item->emit($definitions);
    }
    return '<'.$this->type.'>'.$r.'</'.$this->type.'>';
  }
}