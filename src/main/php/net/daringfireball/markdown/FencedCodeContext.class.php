<?php namespace net\daringfireball\markdown;

class FencedCodeContext extends Context {
  protected $language, $fence;

  public function __construct($language, $fence= '```') {
    $this->language= $language;
    $this->fence= $fence;
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
      if (0 === strncmp($line, $this->fence, 3)) {
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