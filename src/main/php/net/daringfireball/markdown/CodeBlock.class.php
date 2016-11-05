<?php namespace net\daringfireball\markdown;

/**
 * A block of code
 *
 * @test  xp://net.daringfireball.markdown.unittest.CodeTest 
 */
class CodeBlock extends NodeList {
  public $language;

  public function __construct($language= null) {
    $this->language= $language;
  }

  /** @return string */
  public function language() { return $this->language; }

  /** @return string */
  public function code() {
    $r= '';
    foreach ($this->nodes as $node) {
      $r.= "\n".cast($node, 'net.daringfireball.markdown.Text')->value;
    }
    return substr($r, 1);
  }

  /**
   * Emit this node
   *
   * @param  net.daringfireball.markdown.Emitter $emitter
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emit($emitter, $definitions= []) {
    return $emitter->emitCodeBlock($this, $definitions);
  }
}