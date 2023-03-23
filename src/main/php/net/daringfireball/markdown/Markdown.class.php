<?php namespace net\daringfireball\markdown;

use lang\{FormatException, Throwable};

/**
 * Markdown
 *
 * @see  http://daringfireball.net/projects/markdown/basics
 * @see  https://github.com/markdown/markdown.github.com/wiki/Implementations
 * @test xp://net.daringfireball.markdown.unittest.MarkdownClassTest
 */
class Markdown {
  protected $tokens= [];
  protected $handlers= [];

  /**
   * Initializes default tokens and handlers
   */
  public function __construct() {

    // Tokens
    $this->addToken('&', function($line, $target, $ctx) {
      if (-1 === ($s= $line->next(';'))) return false;
      $target->add(new Entity($line->slice($s)));
      return true;
    });
    $this->addToken('`', function($line, $target, $ctx) {
      if ($line->matches('`` ')) {
        $delimiters= [' ``', '``'];
        $offset= 3;
      } else if ($line->matches('``')) {
        $delimiters= '``';
        $offset= -1;
      } else {
        $delimiters= '`';
        $offset= -1;
      }

      // Unmatched backticks - just handle rest of line as text
      if (null === ($code= $line->delimited($delimiters, $offset))) {
        return false;
      }
      $target->add(new Code($code));
      return true;
    });
    $this->addToken('<', function($line, $target, $ctx) {
      if (preg_match('#<(([a-z]+://)[^ >]+)>#', $line, $m, 0, $line->pos())) {
        $target->add(new Link($m[1]));
      } else if (preg_match('#<(([^ @]+)@[^ >]+)>#', $line, $m, 0, $line->pos())) {
        $target->add(new Email($m[1]));
      } else {
        return false;
      }
      $line->forward(strlen($m[0]));
      return true;
    });

    // *Word* => Emphasis, **Word** => Strong emphasis. Can nest other elements!
    // "a * b" or "a ** b" => No, the star must not be followed by whitespace
    $emphasis= function($line, $target, $ctx) {
      $c= $line->chr();
      if ($line->matches($c.$c.$c)) {
        $n= $line->chr(+3);
        if (null === $n || false !== strpos("\r\n\t ", $n)) return false;
        if (null === ($delimited= $line->delimited($c.$c.$c))) return false;
        $node= new Bold();
        $node->add($ctx->tokenize(new Line($delimited), new Italic()));
        $target->add($node);
      } else if ($line->matches($c.$c)) {
        $n= $line->chr(+2);
        if (null === $n || false !== strpos("\r\n\t ", $n)) return false;
        if (null === ($delimited= $line->delimited($c.$c))) return false;
        $target->add($ctx->tokenize(new Line($delimited), new Bold()));
      } else {
        $n= $line->chr(+1);
        if (null === $n || false !== strpos("\r\n\t ", $n)) return false;
        if (null === ($delimited= $line->delimited($c))) return false;
        $target->add($ctx->tokenize(new Line($delimited), new Italic()));
      }
      return true;
    };
    $this->addToken('*', $emphasis);
    $this->addToken('_', $emphasis);

    $this->addToken('~', function($line, $target, $ctx) {
      if ($line->matches('~~')) {
        if (null === ($delimited= $line->delimited('~~'))) return false;
        $target->add($ctx->tokenize(new Line($delimited), new StrikeThrough()));
        return true;
      }
      return false;
    });

    // Links and images: [A link](http://example.com), [A link](http://example.com "Title"),
    // [Google][goog] reference-style link, [Google][] implicit name,and finally [Google] [1] 
    // numeric references (-> spaces allowed!). Images almost identical except for leading
    // exclamation mark, e.g. ![An image](http://example.com/image.jpg)
    $parseLink= function($line, $target, $ctx, $newInstance) {
      $title= null;
      $text= $line->matching('[]');
      $w= false;
      if ($line->matches('(')) {
        sscanf($line->matching('()'), '%[^" ] "%[^")]"', $url, $title);
        $node= $ctx->tokenize(new Line($text), new NodeList());
      } else if ($line->matches('[') || $w= $line->matches(' [')) {
        $line->forward((int)$w);
        $node= new Text($text);
        if ('' === ($ref= $line->ending(']'))) {
          $url= '@'.strtolower($text);
        } else {
          $url= '@'.strtolower($ref);
        }
      } else {
        $target->add(new Text('['.$text.']'));
        return true;
      }
      $target->add($newInstance($url, $node, $title));
      return true;
    };
    $this->addToken('[', function($line, $target, $ctx) use($parseLink) {
      return $parseLink($line, $target, $ctx, function($url, $text, $title) {
        return new Link($url, $text, $title);
      });
    });
    $this->addToken('!', function($line, $target, $ctx) use($parseLink) {
      if (!$line->matches('![')) return false;
      $line->forward(1);
      return $parseLink($line, $target, $ctx, function($url, $text, $title) {
        return new Image($url, $text, $title);
      });
    });

    // Handlers
    // * [id]: http://example.com "Link"
    // * Auto-linkage for http, https and ftp links, *before* any of the below apply!
    // * Atx-style headers "#" -> h1, "##" -> h2, ... etc.
    // * Setext-style headers are "underlined"
    // * "*", "+" or "-" -> ul/li
    // * ">" or "> >" -> block quoting
    // * [0-9]"." -> ol/li
    $this->addHandler('/^\s{0,3}\[([^\]]+)\]:\s+([^ ]+)(.*)/', function($lines, $matches, $result, $ctx) { 
      static $def= ['(' => '()', '"' => '"', "'" => "'"];
      $title= trim($matches[3]);
      if ('' !== $title && 0 === strcspn($title, '(\'"')) {
        $title= trim($title, $def[$title[0]]);
      } else {
        $title= null;
      }
      $result->urls[strtolower($matches[1])]= new Link($matches[2], null, $title);
      return true;
    });
    $this->addHandler('#(^|[^\(\<])((ht|f)tps?://[^ ]+)#', function($lines, $matches, $result, $ctx) { 
      $matches[0]->replace('#(^|[^\(\<])((ht|f)tps?://[^\s]+)($|\s|[.?,;!]\s|[.?,;!]$)#U', '$1<$2>$4');
      return false;   // Further handlers may be applied
    });
    $this->addHandler('/^(#{1,6}) (.+)/', function($lines, $matches, $result, $ctx) {
      $header= $result->append(new Header(substr_count($matches[1], '#')));
      $ctx->tokenize(new Line(rtrim($matches[2], ' #')), $header);
      return true;
    });
    $this->addHandler('/^(={3,}|-{3,})/', function($lines, $matches, $result, $ctx) {
      $paragraph= $result->last();
      if ($text= $paragraph->remove($paragraph->size() - 1)) {
        $result->append(new Header('=' === $matches[1][0] ? 1 : 2))->add($text);
      } else {
        $paragraph->add(new Text($matches[0]));
      }
      return true;
    });
    $this->addHandler('/^(\* ?){3,}$/', function($lines, $matches, $result, $ctx) {
      $result->append(new Ruler());
      return true;
    });
    $this->addHandler('/^[+\*\-] /', function($lines, $matches, $result, $ctx) {
      $lines->resetLine($matches[0]);
      $result->append($ctx->enter(new ListContext('ul'))->parse($lines));
      return true;
    });
    $this->addHandler('/^[0-9]+\. /', function($lines, $matches, $result, $ctx) {
      $lines->resetLine($matches[0]);
      $result->append($ctx->enter(new ListContext('ol'))->parse($lines));
      return true;
    });
    $this->addHandler('/^\> /', function($lines, $matches, $result, $ctx) {
      $lines->resetLine($matches[0]);
      $result->append($ctx->enter(new BlockquoteContext($ctx->handlers))->parse($lines));
      return true;
    });
    $this->addHandler('/^(    |\t)/', function($lines, $matches, $result, $ctx) {
      $lines->resetLine($matches[0]);
      $result->append($ctx->enter(new CodeContext())->parse($lines));
      return true;
    });
    $this->addHandler('/^((`|~){3,})(.*)/', function($lines, $matches, $result, $ctx) {
      $result->append($ctx->enter(new FencedCodeContext(trim($matches[3], ' .'), $matches[1]))->parse($lines));
      return true;
    });
    $this->addHandler('/^\|.+\| *$/', function($lines, $matches, $result, $ctx) {
      $separator= $lines->nextLine();
      if (preg_match('/^\|[ :|-]+\| *$/', (string)$separator)) {
        $result->append($ctx->enter(new WrappedTableContext($matches[0], $separator))->parse($lines));
        return true;
      } else {
        $lines->resetLine($separator);
        return false;
      }
    });
    $this->addHandler('/^(.+\|.+)+$/', function($lines, $matches, $result, $ctx) {
      $separator= $lines->nextLine();
      if (preg_match('/^([ :|-]+\|[ :|-]+)+$/', (string)$separator)) {
        $result->append($ctx->enter(new InlineTableContext($matches[0], $separator))->parse($lines));
        return true;
      } else {
        $lines->resetLine($separator);
        return false;
      }
    });
  }

  /**
   * Adds a handler to parse starting with a given character
   *
   * The handler is a closure of the following form:
   * ```php
   * $handler= function($line, $target, $ctx) {
   *   $target->add(new Code($line->ending('`')));
   *   return true;
   * };
   * ```
   * The tokens handler needs to return whether it handled the token.
   * 
   * @param  string $char A single character starting the token
   * @param  var $handler The closure
   * @return void
   */
  public function addToken($char, $handler) {
    $this->tokens[$char]= $handler;
  }

  /**
   * Adds a handler to parse start of a line
   *
   * The handler is a closure of the following form:
   * ```php
   * $handler= function($lines, $matches, $result, $ctx) {
   *   $result->append(new Ruler());
   *   return true;
   * };
   * ```
   *
   * @param  string $char A single character starting the token
   * @param  var $handler The closure
   * @return void
   */
  public function addHandler($pattern, $handler) {
    $this->handlers[$pattern]= $handler;
  }

  /**
   * Parses the output and returns the resulting parse tree
   *
   * @param  string|net.daringfireball.markdown.Input|io.streams.TextReader $in markdown
   * @return net.daringfireball.markdown.ParseTree
   * @throws lang.FormatException
   */
  public function parse($in) {
    $context= (new ToplevelContext())->withTokens($this->tokens)->withHandlers($this->handlers);
    $input= Input::from($in);
    try {
      return $context->parse($input);
    } catch (Throwable $e) {
      throw new FormatException('Error in '.$input->toString(), $e);
    }
  }

  /**
   * Transform a given input and returns the output
   *
   * @param  string|net.daringfireball.markdown.Input|io.streams.TextReader $in markdown
   * @param  [:net.daringfireball.markdown.Link] $urls
   * @param  net.daringfireball.markdown.Emitter $emitter Defaults to HTML
   * @return string markup
   * @throws lang.FormatException
   */
  public function transform($in, $urls= [], Emitter $emitter= null) {
    return $this->parse($in)->emit($emitter ?: new ToHtml(), array_change_key_case($urls, CASE_LOWER));
  }
}