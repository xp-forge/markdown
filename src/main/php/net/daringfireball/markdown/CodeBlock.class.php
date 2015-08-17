<?php namespace net\daringfireball\markdown;

/**
 * A block of code
 *
 * @test  xp://net.daringfireball.markdown.unittest.CodeTest 
 */
class CodeBlock extends NodeList {
  protected $language= '';

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
   * Emit this code block
   *
   * @param	 [:net.daringfireball.markdown.Link] definitions
   * @return string
   */
  public function emit($definitions) {
    $r= '';
    for ($i= 0, $s= sizeof($this->nodes); $i < $s; $i++) {
      $r.= $this->nodes[$i]->emit($definitions);
      if ($i < $s - 1) $r.= "\n";
    }
    $attr= $this->language ? ' lang="'.htmlspecialchars($this->language).'"' : '';
    return '<code'.$attr.'>'.$r.'</code>';
  }
}