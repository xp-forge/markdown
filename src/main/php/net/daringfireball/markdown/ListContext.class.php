<?php namespace net\daringfireball\markdown;

class ListContext extends Context {
  protected $type= '';

  /**
   * Creates a new list context
   *
   * @param  string type
   * @param  int level
   */
  public function __construct($type, $level= 0) {
    $this->type= $type;
    $this->level= $level;
  }

  public function parse($lines) {
    $empty= false;
    $target= null;
    $result= new Listing($this->type);
    while ($lines->hasMoreTokens()) {
      $line= new Line($lines->nextToken());

      // An empty line makes the list use paragraphs, except if it's the last line.
      if (0 === $line->length()) {
        $empty= true;
        continue;
      }

      // Indented elements form additional paragpraphs inside list items. If 
      // the line doesn't start with a list bullet, this means the list is at
      // its end.
      if (preg_match('/^(\s+)?([+*-]+|[0-9]+\.) /', $line, $m)) {
        $empty && $result->paragraphs= true;
        $empty= false;

        // Check whether we need to indent / dedent the list level, or whether
        // the list item belongs to this list
        $level= strlen($m[1]) / 2;
        if ($level > $this->level) {
          $lines->pushBack($line."\n");
          $target= $target ?: $result->add(new ListItem())->add(new Paragraph());
          $target->add($this->enter(new self($this->type, $level))->parse($lines));
        } else if ($level < $this->level) {
          $lines->pushBack($line."\n");
          break;
        } else {
          $target= $result->add(new ListItem())->add(new Paragraph());
          $line->forward(strlen($m[0]));
          $this->tokenizer->tokenize($line, $target);
        }
      } else if ('  ' === substr($line, 0, 2)) {

        // Add paragraph to existing list item
        $paragraph= $result->last()->add(new Paragraph());
        $line->forward(2);
        $this->tokenizer->tokenize($line, $paragraph);
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
    return $this->type;
  }
}