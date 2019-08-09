<?php namespace net\daringfireball\markdown\unittest;

use io\File;
use io\streams\LinesIn;
use net\daringfireball\markdown\Markdown;
use unittest\AssertionFailedError;
use unittest\TestCase;

class CommonMarkSpecTest extends TestCase {
  private $spec;

  /**
   * Instantiate spec test
   *
   * @param  string $name
   * @param  string $spec Path to specs.txt
   * @see    https://github.com/commonmark/commonmark-spec
   */
  public function __construct($name, $spec) {
    parent::__construct($name);
    $this->spec= new File($spec);
  }

  /** @return iterable */
  private function tests() {
    $example= null;
    foreach (new LinesIn($this->spec) as $line) {
      if ('```````````````````````````````` example' === $line) {
        $example= ['', ''];
        $target= 0;
      } else if ('````````````````````````````````' === $line) {
        yield $example;
        $example= null;
      } else if ($example && '.' === $line) {
        $target= 1;
      } else if ($example) {
        $example[$target].= str_replace('→', "\t", $line)."\n";
      }
    }
  }

  #[@test, @values('tests')]
  public function verify($input, $expected) {
    $transformed= (new Markdown())->transform($input);
    if (trim($expected) !== trim($transformed)) {
      throw new AssertionFailedError(sprintf(
        "Not spec-conformant:\nInput       '%s'\nExpected    '%s'\nTransformed '%s'",
        addcslashes($input, "\0..\17!\177..\377"),
        addcslashes($expected, "\0..\17!\177..\377"),
        addcslashes($transformed, "\0..\17!\177..\377")
      ));
    }
  }
}