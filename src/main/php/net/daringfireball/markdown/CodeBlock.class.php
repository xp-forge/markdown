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