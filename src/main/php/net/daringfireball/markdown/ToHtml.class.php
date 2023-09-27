<?php namespace net\daringfireball\markdown;

/**
 * Emits markdown as HTML
 *
 * @test net.daringfireball.markdown.unittest.ToHtmlTest
 */
class ToHtml extends Emitter {
  protected $urls, $flags;

  /**
   * Creates a new emitter
   *
   * @param  net.daringfireball.markdown.URLs $urls Optional urls resolver
   * @param  int $flags
   */
  public function __construct(URLs $urls= null, $flags= ENT_COMPAT) {
    $this->urls= $urls ?: new URLs();
    $this->flags= $flags;
  }

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
   * Emits a header
   *
   * @param  net.daringfireball.markdown.Header $paragraph
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitHeader($header, $definitions) {
    return '<h'.$header->level.'>'.$this->emitAll($header->all(), $definitions).'</h'.$header->level.'>';
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
   * Emits a blockquote
   *
   * @param  net.daringfireball.markdown.BlockQuote $blockquote
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitBlockQuote($blockquote, $definitions) {
    return '<blockquote>'.$this->emitAll($blockquote->all(), $definitions).'</blockquote>';
  }

  /**
   * Emits a ruler
   *
   * @param  net.daringfireball.markdown.Ruler $ruler
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitRuler($ruler, $definitions) {
    return '<hr>';
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
    return htmlspecialchars($text->value, $this->flags);
  }

  /**
   * Emits a link
   *
   * @param  net.daringfireball.markdown.Link $link
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitLink($link, $definitions) {
    $attr= $link->title ? ' title="'.htmlspecialchars($link->title, $this->flags).'"' : '';
    $text= $link->text ? $link->text->emit($this, $definitions) : $link->url;
    return '<a href="'.htmlspecialchars($this->urls->href($link), $this->flags).'"'.$attr.'>'.$text.'</a>';
  }

  /**
   * Emits an image
   *
   * @param  net.daringfireball.markdown.Image $image
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitImage($image, $definitions) {
    $attr= '';
    $image->text && $attr.= ' alt="'.$image->text->emit($this, $definitions).'"';
    $image->title && $attr.= ' title="'.htmlspecialchars($image->title, $this->flags).'"';
    return '<img src="'.htmlspecialchars($this->urls->src($image), $this->flags).'"'.$attr.' />';
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
      $encoded.= '&#x'.dechex(ord($email->address[$i])).';';
    }

    // An encoded "mailto:" (with "i" and ":" in plain)
    return '<a href="&#x6D;&#x61;i&#x6C;&#x74;&#x6F;:'.$encoded.'">'.$encoded.'</a>';
  }

  /**
   * Emits an entity
   *
   * @param  net.daringfireball.markdown.Entity $entity
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitEntity($entity, $definitions) {
    return $entity->value;
  }

  /**
   * Emits a line break
   *
   * @param  net.daringfireball.markdown.LineBreak $br
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitLineBreak($br, $definitions) {
    return '<br>';
  }

  /**
   * Emits an inline code fragment
   *
   * @param  net.daringfireball.markdown.Code $code
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCode($code, $definitions) {
    return '<code>'.htmlspecialchars($code->value, $this->flags).'</code>';
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
    foreach ($block->all() as $i => $node) {
      $r.= $node->emit($this, $definitions)."\n";
    }

    $attr= $block->language ? ' class="language-'.htmlspecialchars($block->language, $this->flags).'"' : '';
    return '<pre><code'.$attr.'>'.$r.'</code></pre>';
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
    if ($item->list->paragraphs) return '<li>'.$this->emitAll($item->all(), $definitions).'</li>';  

    // First element outside of paragraph, emit rest
    return
      '<li>'.
      $this->emitAll($item->get(0)->all(), $definitions).
      $this->emitAll($item->slice(1), $definitions).
      '</li>'
    ;
  }
}