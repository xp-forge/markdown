<?php namespace net\daringfireball\markdown;

use io\streams\TextReader;

/**
 * Input implementation for stream readers
 *
 * @test  xp://net.daringfireball.markdown.unittest.ReaderInputTest
 */
class ReaderInput extends Input {
  protected $reader= null;

  /**
   * Creates a new reader input instance
   *
   * @param  io.streams.TextReader $reader
   */
  public function __construct(TextReader $reader) {
    $this->reader= $reader;
  }

  /**
   * Reads a line
   *
   * @return string or NULL to indicate EOF
   */
  protected function readLine() {
    return $this->reader->readLine();
  }

  /**
   * Returns a description of the source for use in `toString()`
   *
   * @return string
   */
  protected function sourceDescription() {
    return $this->reader->stream()->toString();
  }
}