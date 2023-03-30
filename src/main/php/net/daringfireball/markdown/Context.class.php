<?php namespace net\daringfireball\markdown;

use util\Objects;
use lang\Value;

abstract class Context implements Value {
  protected $tokens= [];
  protected $handlers= [];
  protected $span= '';

  /**
   * Enters subcontext
   *
   * @param  self $context
   * @return self
   */
  public function enter(self $context) {
    $context->tokens= $this->tokens;
    $context->handlers= $this->handlers;
    $context->span= $this->span;
    return $context;
  }

  /**
   * Sets token handlers
   * 
   * @param  [:var] $tokens
   * @return self
   * @see    net.daringfireball.markdown.Markdown::addToken
   */
  public function withTokens($tokens) {
    $this->tokens= $tokens;
    $this->span= '\\'.implode('', array_keys($tokens));
    return $this;
  }

  /**
   * Sets line handlers
   *
   * @param  [:var] $handlers
   * @return self
   * @see    net.daringfireball.markdown.Markdown::addHandler
   */
  public function withHandlers($handlers) {
    $this->handlers= $handlers;
    return $this;
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
        $t= $line[$line->pos() + 1];
        $line->forward(2);          // Skip escape, don't tokenize next character
      } else if (isset($this->tokens[$c])) {
        if (!$this->tokens[$c]($line, $target, $this)) {
          $t= $c;                   // Push back
          $line->forward();
        }
      }

      // Optimization: Do not create empty text nodes
      if ('' !== ($t.= $line->until($this->span))) {
        $target->add(new Text($t));
      }

      if ($safe++ > $l) throw new \lang\IllegalStateException('Endless loop detected');
    }
    return $target;
  }

  /** @return string */
  public function toString() {
    $s= 'net.daringfireball.markdown.Context('.$this->name();
    $parent= $this->parent;
    while (null !== $parent) {
      $s.= ' > '.$parent->name();
      $parent= $parent->parent;
    }
    return $s.')';
  }

  /** @return string */
  public function hashCode() {
    return 'C'.Objects::hashOf([$this->tokens, $this->span]);
  }

  /**
   * Compare
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([$this->tokens, $this->span], [$value->tokens, $value->span])
      : 1
    ;
  }
}