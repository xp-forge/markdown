<?php namespace net\daringfireball\markdown\unittest;

class HeadersTest extends MarkdownTest {

  #[@test]
  public function first_level_header() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header');
  }

  #[@test]
  public function first_level_header_closed() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header #');
  }

  #[@test]
  public function first_level_header_closed_with_non_matching_hashes() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header #####');
  }

  #[@test, @ignore('Borken')]
  public function first_level_header_with_underline() {
    $this->assertTransformed(
      '<h1>A First Level Header</h1>', 
      "A First Level Header\n".
      "===================="
    );
  }

  #[@test]
  public function second_level_header() {
    $this->assertTransformed('<h2>A Second Level Header</h2>', '## A Second Level Header');
  }

  #[@test, @ignore('Borken')]
  public function second_level_header_with_underline() {
    $this->assertTransformed(
      '<h2>A Second Level Header</h2>', 
      "A Second Level Header\n".
      "---------------------"
    );
  }

  #[@test]
  public function third_level_header() {
    $this->assertTransformed('<h3>A Third Level Header</h3>', '### A Third Level Header');
  }
}