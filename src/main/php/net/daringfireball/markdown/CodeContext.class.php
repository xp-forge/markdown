<?php namespace net\daringfireball\markdown;

class CodeContext extends Context {

  public function parse($lines) {
    $result= new CodeBlock();

    while ($lines->hasMoreTokens()) {
      $line= $lines->nextToken();
      if ("\t" === $line{0}) {
        $result->add(new Text(substr($line, 1)));
      } else if (0 === strncmp($line, '    ', 4)) {
        $result->add(new Text(substr($line, 4)));
      } else {
        $lines->pushBack($line."\n");
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