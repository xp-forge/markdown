<?php namespace net\daringfireball\markdown\unittest;

use io\File;
use io\streams\LinesIn;
use net\daringfireball\markdown\{Markdown, ToHtml};
use test\{Args, Assert, AssertionFailed, Test, Values};

#[Args('spec')]
class CommonMarkSpecTest {
  private $spec, $emit;

  /**
   * Instantiate spec test
   *
   * @param  string $spec Path to specs.txt
   * @see    https://github.com/commonmark/commonmark-spec
   */
  public function __construct($spec) {
    $this->spec= new File($spec);
    $this->emit= new class() extends ToHtml {

      public function emitParagraph($paragraph, $definitions) {
        return '<p>'.$this->emitAll($paragraph->all(), $definitions)."</p>\n";
      }

      public function emitLineBreak($br, $definitions) {
        return "<br />\n";
      }

      public function emitRuler($ruler, $definitions) {
        return '<hr />';
      }

      public function emitHeader($header, $definitions) {
        return "<h{$header->level}>{$this->emitAll($header->all(), $definitions)}</h{$header->level}>\n";
      }

      public function emitBlockQuote($blockquote, $definitions) {
        return "<blockquote>\n{$this->emitAll($blockquote->all(), $definitions)}\n</blockquote>\n";
      }

      public function emitListing($listing, $definitions) {
        $list= "<{$listing->type}>\n";
        foreach ($listing->all() as $node) {
          $list.= $node->emit($this, $definitions)."\n";
        }
        return $list."</{$listing->type}>\n";
      }

      public function emitEmail($email, $definitions) {
        $address= htmlspecialchars($email->address);
        return "<a href=\"mailto:{$address}\">{$address}</a>";
      }
    };
  }

  /** @return iterable */
  private function tests() {
    static $ignored= ['Raw HTML' => true];

    $example= $section= null;
    foreach (new LinesIn($this->spec) as $line) {
      if ('```````````````````````````````` example' === $line) {
        $example= ['', ''];
        $target= 0;
      } else if ('````````````````````````````````' === $line) {
        isset($ignored[$section]) || yield $example;
        $example= null;
      } else if ($example && '.' === $line) {
        $target= 1;
      } else if ($example) {
        $example[$target].= str_replace('→', "\t", $line)."\n";
      } else if (0 === strncmp($line, '## ', 3)) {
        $section= trim(substr($line, 3));
      }
    }
  }

  #[Test, Values(from: 'tests')]
  public function verify($input, $expected) {
    $transformed= (new Markdown())->transform(trim($input), [], $this->emit);
    if (trim($expected) !== trim($transformed)) {
      throw new AssertionFailed(sprintf(
        "the implementation is spec-conformant:\nInput       '%s'\nExpected    '%s'\nTransformed '%s'",
        addcslashes(trim($input), "\0..\17!\177..\377"),
        addcslashes(trim($expected), "\0..\17!\177..\377"),
        addcslashes(trim($transformed), "\0..\17!\177..\377")
      ));
    }
  }
}