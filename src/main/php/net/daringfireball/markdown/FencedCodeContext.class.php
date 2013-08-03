<?php namespace net\daringfireball\markdown;

class FencedCodeContext extends Context {
  protected $language= '';

  public function __construct($language) {
    $this->language= $language;
  }

  /**
   * Parse input into nodes
   *
   * @param  net.daringfireball.markdown.Input $lines
   * @return net.daringfireball.markdown.Node
   */
  public function parse($lines) {
    $result= new CodeBlock($this->language);

    while ($lines->hasMoreLines()) {
      $line= $lines->nextLine();
      if (0 === strncmp($line, '```', 3)) {
        break;
      } else {
        $result->add(new Text($line));
      }
    }

    return $result;
  }

  /**
   * Returns this context's name
   *
   * @return string
   */
  public function name() {
    return 'code'.($this->language ? '(language= '.$this->language.')' : '');
  }
}