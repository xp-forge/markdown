<?php namespace net\daringfireball\markdown;

interface Emitter {

  /**
   * Emits a parse tree
   *
   * @param  net.daringfireball.markdown.ParseTree $tree
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitParseTree($tree, $definitions);

  /**
   * Emits a node list
   *
   * @param  net.daringfireball.markdown.NodeList $list
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitNodeList($list, $definitions);

  /**
   * Emits a header
   *
   * @param  net.daringfireball.markdown.Header $paragraph
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitHeader($header, $definitions);

  /**
   * Emits a paragraph
   *
   * @param  net.daringfireball.markdown.Paragraph $paragraph
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitParagraph($paragraph, $definitions);

  /**
   * Emits a blockquote
   *
   * @param  net.daringfireball.markdown.BlockQuote $blockquote
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitBlockQuote($blockquote, $definitions);

  /**
   * Emits a ruler
   *
   * @param  net.daringfireball.markdown.Ruler $ruler
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitRuler($ruler, $definitions);

  /**
   * Emits strike-through text
   *
   * @param  net.daringfireball.markdown.StrikeThrough $node
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitStrikeThrough($node, $definitions);

  /**
   * Emits italic text
   *
   * @param  net.daringfireball.markdown.Italic $node
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitItalic($node, $definitions);

  /**
   * Emits bold text
   *
   * @param  net.daringfireball.markdown.Bold $node
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitBold($node, $definitions);

  /**
   * Emits a table
   *
   * @param  net.daringfireball.markdown.Table $table
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitTable($table, $definitions);

  /**
   * Emits a table row
   *
   * @param  net.daringfireball.markdown.Row $row
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitRow($row, $definitions);

  /**
   * Emits a table cell
   *
   * @param  net.daringfireball.markdown.Cell $cell
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCell($cell, $definitions);

  /**
   * Emits a text fragment
   *
   * @param  net.daringfireball.markdown.Text $text
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitText($text, $definitions);

  /**
   * Emits a link
   *
   * @param  net.daringfireball.markdown.Link $link
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitLink($link, $definitions);

  /**
   * Emits an image
   *
   * @param  net.daringfireball.markdown.Image $image
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitImage($image, $definitions);

  /**
   * Emits an email address
   *
   * @param  net.daringfireball.markdown.Email $email
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitEmail($email, $definitions);

  /**
   * Emits an entity
   *
   * @param  net.daringfireball.markdown.Entity $entity
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitEntity($entity, $definitions);

  /**
   * Emits an inline code fragment
   *
   * @param  net.daringfireball.markdown.Code $code
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCode($code, $definitions);

  /**
   * Emits a code block
   *
   * @param  net.daringfireball.markdown.CodeBlock $block
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitCodeBlock($block, $definitions);

  /**
   * Emits a listing (ordered or unordered)
   *
   * @param  net.daringfireball.markdown.Listing $listing
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitListing($listing, $definitions);

  /**
   * Emits a list item
   *
   * @param  net.daringfireball.markdown.ListItem $item
   * @param  [:net.daringfireball.markdown.Link] $definitions
   * @return string
   */
  public function emitListItem($item, $definitions);
}