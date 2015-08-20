<?php namespace net\daringfireball\markdown;

abstract class Context extends \lang\Object {
  protected $tokens= [];
  protected $span= '';

  public function enter(self $context) {
    $context->tokens= $this->tokens;
    $context->span= $this->span;
    return $context;
  }

  /**
   * Sets token handlers
   * 
   * @param [:var] tokens
   * @see   xp://net.daringfireball.markdown.Markdown#addToken
   */
  public function setTokens($tokens) {
    $this->tokens= $tokens;
    $this->span= '\\'.implode('', array_keys($tokens));
  }

  /**
   * Parse input into nodes
   *
   * @param  net.daringfireball.markdown.Input $lines
   * @return net.daringfireball.markdown.Node
   */
  public abstract function parse($lines);

  /**
   * Returns this context's name
   *
   * @return string
   */
  public abstract function name();

  /**
   * Tokenize a line
   *
   * @param  net.daringfireball.markdown.Line $l The line
   * @param  net.daringfireball.markdown.Node $target The target node to add nodes to
   * @return net.daringfireball.markdown.Node The target
   */
  public function tokenize(Line $line, Node $target) {
    $safe= 0;
    $l= $line->length();
    while ($line->pos() < $l) {
      $t= '';
      $c= $line->chr();
      if ('\\' === $c) {
        $t= $line{$line->pos() + 1};
        $line->forward(2);          // Skip escape, don't tokenize next character
      } else if (isset($this->tokens[$c])) {
        if (!$this->tokens[$c]($line, $target, $this)) {
          $t= $c;                   // Push back
          $line->forward();
        }
      }

      $target->add(new Text($t.$line->until($this->span)));
      if ($safe++ > $l) throw new \lang\IllegalStateException('Endless loop detected');
    }
    return $target;
  }

  /**
   * Creates a string representation of this context
   *
   * @return string
   */
  public function toString() {
    $s= 'net.daringfireball.markdown.Context('.$this->name();
    $parent= $this->parent;
    while (null !== $parent) {
      $s.= ' > '.$parent->name();
      $parent= $parent->parent;
    }
    return $s.')';
  }
}