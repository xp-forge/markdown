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

  #[@test]
  public function second_level_header() {
    $this->assertTransformed('<h2>A Second Level Header</h2>', '## A Second Level Header');
  }

  #[@test]
  public function third_level_header() {
    $this->assertTransformed('<h3>A Third Level Header</h3>', '### A Third Level Header');
  }

  #[@test]
  public function fourth_level_header() {
    $this->assertTransformed('<h4>A Fourth Level Header</h4>', '#### A Fourth Level Header');
  }

  #[@test]
  public function fifth_level_header() {
    $this->assertTransformed('<h5>A Fifth Level Header</h5>', '##### A Fifth Level Header');
  }

  #[@test]
  public function sixth_level_header() {
    $this->assertTransformed('<h6>A Sixth Level Header</h6>', '###### A Sixth Level Header');
  }

  #[@test, @ignore('Borken')]
  public function first_level_header_with_underline() {
    $this->assertTransformed(
      '<h1>A First Level Header</h1>', 
      "A First Level Header\n".
      "===================="
    );
  }

  #[@test, @ignore('Borken')]
  public function second_level_header_with_underline() {
    $this->assertTransformed(
      '<h2>A Second Level Header</h2>', 
      "A Second Level Header\n".
      "---------------------"
    );
  }
}