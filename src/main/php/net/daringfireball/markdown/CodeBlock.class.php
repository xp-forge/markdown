<?php namespace net\daringfireball\markdown;

/**
 * A block of code
 *
 * @test  xp://net.daringfireball.markdown.unittest.CodeTest 
 */
class CodeBlock extends NodeList {

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
    return '<code>'.$r.'</code>';
  }
}