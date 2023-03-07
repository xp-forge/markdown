<?php namespace net\daringfireball\markdown\unittest;

use test\Assert;
use test\Test;

class HeadersTest extends MarkdownTest {

  #[Test]
  public function first_level_header() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header');
  }

  #[Test]
  public function second_level_header() {
    $this->assertTransformed('<h2>A Second Level Header</h2>', '## A Second Level Header');
  }

  #[Test]
  public function third_level_header() {
    $this->assertTransformed('<h3>A Third Level Header</h3>', '### A Third Level Header');
  }

  #[Test]
  public function fourth_level_header() {
    $this->assertTransformed('<h4>A Fourth Level Header</h4>', '#### A Fourth Level Header');
  }

  #[Test]
  public function fifth_level_header() {
    $this->assertTransformed('<h5>A Fifth Level Header</h5>', '##### A Fifth Level Header');
  }

  #[Test]
  public function sixth_level_header() {
    $this->assertTransformed('<h6>A Sixth Level Header</h6>', '###### A Sixth Level Header');
  }

  #[Test]
  public function first_level_header_closed() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header #');
  }

  #[Test]
  public function first_level_header_closed_with_non_matching_hashes() {
    $this->assertTransformed('<h1>A First Level Header</h1>', '# A First Level Header #####');
  }

  #[Test]
  public function first_level_header_with_underline() {
    $this->assertTransformed(
      '<h1>A First Level Header</h1>', 
      "A First Level Header\n".
      "===================="
    );
  }

  #[Test]
  public function second_level_header_with_underline() {
    $this->assertTransformed(
      '<h2>A Second Level Header</h2>', 
      "A Second Level Header\n".
      "---------------------"
    );
  }

  #[Test]
  public function markdown_consisting_solely_of_underline() {
    $this->assertTransformed(
      '<p>====================</p>',
      "===================="
    );
  }

  #[Test]
  public function underline_directly_at_beginning_of_text() {
    $this->assertTransformed(
      '<p>====================</p><p>An overline</p>',
      "====================\nAn overline"
    );
  }
}