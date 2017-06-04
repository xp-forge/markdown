<?php namespace net\daringfireball\markdown;

/**
 * Emits markdown as HTML
 *
 * @test xp://net.daringfireball.markdown.unittest.ToHtmlTest
 */
class ToHtml implements Emitter {

  /**
   * Emits a list of nodes
   *
   * @param  net.daringfireball.markdown.Node[] $list
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  private function emitAll($list, $definitions) {
    $r= '';
    foreach ($list as $node) {
      $r.= $node->emit($this, $definitions);
    }
    return $r;
  }

  /**
   * Emits a parse tree
   *
   * @param  net.daringfireball.markdown.ParseTree $tree
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitParseTree($tree, $definitions) {
    return $this->emitAll($tree->all(), $tree->urls + $definitions);
  }

  /**
   * Emits a node list
   *
   * @param  net.daringfireball.markdown.NodeList $list
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitNodeList($list, $definitions) {
    return $this->emitAll($list->all(), $definitions);
  }

  /**
   * Emits a paragraph
   *
   * @param  net.daringfireball.markdown.Paragraph $paragraph
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitParagraph($paragraph, $definitions) {
    return '<p>'.$this->emitAll($paragraph->all(), $definitions).'</p>';
  }

  /**
   * Emits strike-through text
   *
   * @param  net.daringfireball.markdown.StrikeThrough $node
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitStrikeThrough($node, $definitions) {
    return '<del>'.$this->emitAll($node->all(), $definitions).'</del>';
  }

  /**
   * Emits italic text
   *
   * @param  net.daringfireball.markdown.Italic $node
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitItalic($node, $definitions) {
    return '<em>'.$this->emitAll($node->all(), $definitions).'</em>';
  }

  /**
   * Emits bold text
   *
   * @param  net.daringfireball.markdown.Bold $node
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitBold($node, $definitions) {
    return '<strong>'.$this->emitAll($node->all(), $definitions).'</strong>';
  }

  /**
   * Emits a table
   *
   * @param  net.daringfireball.markdown.Table $table
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitTable($table, $definitions) {
    return '<table>'.$this->emitAll($table->rows(), $definitions).'</table>';
  }

  /**
   * Emits a table row
   *
   * @param  net.daringfireball.markdown.Row $row
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitRow($row, $definitions) {
    return '<tr>'.$this->emitAll($row->cells(), $definitions).'</tr>';
  }

  /**
   * Emits a table cell
   *
   * @param  net.daringfireball.markdown.Cell $cell
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCell($cell, $definitions) {
    $attr= $cell->alignment ? ' style="text-align: '.$cell->alignment.'"' : '';
    return '<'.$cell->type.$attr.'>'.$this->emitAll($cell->all(), $definitions).'</'.$cell->type.'>';
  }

  /**
   * Emits a text fragment
   *
   * @param  net.daringfireball.markdown.Text $text
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitText($text, $definitions) {

    // If the string ends with two or more spaces, we have a manual line break.
    $sp= 0;
    for ($i= strlen($text->value)- 1; $i > 0 && ' ' === $text->value{$i}; $i--) {
      $sp++;
    }
    if ($sp >= 2) {
      return htmlspecialchars(substr($text->value, 0, -$sp)).'<br/>';
    } else {
      return htmlspecialchars($text->value);
    }
  }

  /**
   * Emits a link
   *
   * @param  net.daringfireball.markdown.Link $link
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitLink($link, $definitions) {
    if ('@' === $link->url{0}) {
      $target= $definitions[substr($link->url, 1)];
    } else {
      $target= $link;
    }
    $attr= $target->title ? ' title="'.htmlspecialchars($target->title).'"' : '';
    $text= $link->text ? $link->text->emit($this, $definitions) : $target->url;
    return '<a href="'.htmlspecialchars($target->url).'"'.$attr.'>'.$text.'</a>';
  }

  /**
   * Emits an image
   *
   * @param  net.daringfireball.markdown.Image $image
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitImage($image, $definitions) {
    if ('@' === $image->url{0}) {
      $target= $definitions[substr($image->url, 1)];
    } else {
      $target= $image;
    }
    $attr= '';
    $image->text && $attr.= ' alt="'.$image->text->emit($this, $definitions).'"';
    $target->title && $attr.= ' title="'.htmlspecialchars($target->title).'"';
    return '<img src="'.htmlspecialchars($target->url).'"'.$attr.'/>';
  }

  /**
   * Emits an email address
   *
   * @param  net.daringfireball.markdown.Email $email
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitEmail($email, $definitions) {
    $encoded= '';
    for ($i= 0, $s= strlen($email->address); $i < $s; $i++) {
      $encoded.= '&#x'.dechex(ord($email->address{$i})).';';
    }

    // An encoded "mailto:" (with "i" and ":" in plain)
    return '<a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:'.$encoded.'">'.$encoded.'</a>';
  }

  /**
   * Emits an inline code fragment
   *
   * @param  net.daringfireball.markdown.Code $code
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCode($code, $definitions) {
    return '<code>'.htmlspecialchars($code->value).'</code>';
  }

  /**
   * Emits a code block
   *
   * @param  net.daringfireball.markdown.CodeBlock $block
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCodeBlock($block, $definitions) {
    $r= '';
    $s= $block->size() - 1;
    foreach ($block->all() as $i => $node) {
      $r.= $node->emit($this, $definitions);
      if ($i < $s) $r.= "\n";
    }

    $attr= $block->language ? ' lang="'.htmlspecialchars($block->language).'"' : '';
    return '<code'.$attr.'>'.$r.'</code>';
  }

  /**
   * Emits a listing (ordered or unordered)
   *
   * @param  net.daringfireball.markdown.Listing $listing
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitListing($listing, $definitions) {
    return '<'.$listing->type.'>'.$this->emitAll($listing->all(), $definitions).'</'.$listing->type.'>';
  }

  /**
   * Emits a list item
   *
   * @param  net.daringfireball.markdown.ListItem $item
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitListItem($item, $definitions) {
    return '<li>'.$this->emitAll($item->list->paragraphs ? $item->all() : $item->get(0)->all(), $definitions).'</li>';
  }
}