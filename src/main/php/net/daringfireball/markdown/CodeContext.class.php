<?php namespace net\daringfireball\markdown;

class CodeContext extends Context {

  public function parse($lines) {
    $result= new CodeBlock();

    while ($lines->hasMoreLines()) {
      $line= $lines->nextLine();
      if ("\t" === $line->chr()) {
        $result->add(new Text(substr($line, 1)));
      } else if (0 === strncmp($line, '    ', 4)) {
        $result->add(new Text(substr($line, 4)));
      } else {
        $lines->resetLine($line);
        break;
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
    return 'code';
  }
}