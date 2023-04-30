Markdown for XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

* Fixed auto-linking inside braces - @thekid

## 7.1.1 / 2023-04-02

* Fixed auto-linking inside lists - @thekid

## 7.1.0 / 2023-03-30

* Fixed code blocks inside a block quote - @thekid
* Removed `setTokens()` and `setHandlers()` from contexts - @thekid
* Merged PR #19: Fix formatting inside lists - @thekid

## 7.0.0 / 2023-03-23

* Implemented xp-framework/rfc#341: Drop support for XP 9 and lower
  (@thekid)
* Merged PR #14: Spec compliance testing. Not run in the test suite yet!
  (@thekid)
* **Heads up:** No longer use the `lang` attribute on `<code>`. Instead,
  use `class="language-..."` in compliance with spec.
  (@thekid)
* Merged PR #17: Emit code blocks as `<code>` wrapped in `<pre>`. Solves
  problem described in issue #16.
  (@thekid)

## 6.1.0 / 2023-03-23

* Merged PR #18: Add support for fenced code blocks enclosed with `~~~`
  (@thekid)
* Merged PR #15: Migrate to new testing library - @thekid

## 6.0.2 / 2022-02-26

* Fixed "Creation of dynamic property" warnings in PHP 8.2 - @thekid

## 6.0.1 / 2020-10-21

* Made compatible with PHP 8.1 using `#[ReturnTypeWillChange]` attributes,
  preventing *null* values for `preg_match()` and using *ENT_COMPAT* for
  `htmlspecialchars()` flags
  (@thekid)

## 6.0.0 / 2020-04-10

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  . Rewrote code base, grouping use statements
  . Converted `newinstance` to anonymous classes
  . Rewrote `isset(X) ? X : default` to `X ?? default`
  (@thekid)

## 5.2.2 / 2020-04-05

* Implemented RFC #335: Remove deprecated key/value pair annotation syntax
  (@thekid)

## 5.2.1 / 2019-12-01

* Made compatible with XP 10 - @thekid

## 5.2.0 / 2019-08-09

* Added PHP 7.4 support - @thekid
* Added support for block quotes inside block quotes - @thekid
* Fixed formatting inside block quotes - @thekid

## 5.1.0 / 2019-05-06

* Merged PR #13: URL rewriting - @thekid
* Added PHP 7.3 support - @thekid

## 5.0.0 / 2018-07-22

* Changed emitter to also emit headers, blockquotes, rulers and entities
  **Heads up**: `net.daringfireball.markdown.Emitter` interface contains
  four new corresponding methods which now need to be implemented!
  (@thekid)

## 4.1.1 / 2018-07-22

* Fixed issue #12: Call to a member function length() on null - @thekid

## 4.1.0 / 2017-11-13

* Merged PR #11: Also accept io.streams.TextReader instances - @thekid

## 4.0.0 / 2017-06-04

* **Heads up:** Dropped PHP 5.5 support - @thekid
* Added forward compatibility with XP 9.0.0 - @thekid

## 3.2.4 / 2016-12-29

* Fixed bug with nesting of emphasis - @thekid

## 3.2.3 / 2016-11-07

* Fixed tables with extra columns not "declared" in header - @thekid

## 3.2.2 / 2016-11-07

* Change parsed to handle unclosed inline code fragments gracefully
  (@thekid)

## 3.2.1 / 2016-11-07

* Fixed text in square braces not followed by neither `(...)` nor `[...]`
  (@thekid)
* Fixed underlines directly at the beginning of the markdown string
  (@thekid)
* Fixed errors when removing non-existant elements from a NodeList
  (@thekid)

## 3.2.0 / 2016-11-05

* Merged PR #9: Extract emitting HTML from nodes into emitter class
  (@thekid)

## 3.1.2 / 2016-11-04

* Fixed lists followed by horizontal rulers - @thekid

## 3.1.1 / 2016-11-04

* Optimized parser to no longer create empty text nodes
  (@thekid)
* Fixed tables with empty cells causing *Invalid argument* warning
  (@thekid)
* Fixed string offset reading which could lead to parse exceptions
  (@thekid)

## 3.1.0 / 2016-08-28

* Added forward compatibility with XP 8.0.0 - @thekid

## 3.0.0 / 2016-02-21

* Added version compatibility with XP 7 - @thekid

## 2.0.1 / 2016-01-23

* Fix code to use `nameof()` instead of the deprecated `getClassName()`
  method from lang.Generic. See xp-framework/core#120
  (@thekid)

## 2.0.0 / 2015-12-14

* **Heads up**: Changed minimum XP version to XP 6.5.0, and with it the
  minimum PHP version to PHP 5.5.
  (@thekid)

## 1.1.1 / 2015-08-25

* Merged pull request #8: Strike through - @thekid

## 1.1.0 / 2015-08-20

* Merged pull request #7: Table support - @thekid
* Added new `Markdown::parse()` method which will return a parse tree
  instead of directly transforming it. This tree can be reused multiple
  times later on.
  (@thekid)

## 1.0.2 / 2015-07-12

* Added forward compatibility with XP 6.4.0 - @thekid
* Added preliminary PHP 7 support (alpha2, beta1) - @thekid

## 1.0.1 / 2015-02-12

* Changed dependency to use XP ~6.0 (instead of dev-master) - @thekid

## 1.0.0 / 2015-01-10

* Made available via Composer - (@thekid)

## 0.9.4 / 2014-09-23

* Fixed `a * b` leading to formatting errors - (@thekid)
* Added source description and line number to error messages - (@thekid)
* Fixed issue #5: Support nesting - (@thekid)

## 0.9.3 / 2013-08-04

* Fixed URL identifiers passed to Markdown::transform() to be handled
  case insensitively - (@thekid)
* Implemented auto-linking for http, https and ftp links - (@thekid)
* Added support for Windows (`\r\n`), Un*x (`\n`) and Mac (`\r`) line
  endings in string input - (@thekid)

## 0.9.2 / 2013-08-03

* Implemented feature request #2: Support GitHub-style fenced code blocks
  (@thekid)
* Made line handlers extensible via addHandler() - (@thekid)
* Heads up: Changed token handlers API to addToken() - (@thekid)
* Added possibility to pass predefined urls to transform() - (@thekid)

## 0.9.1 / 2013-08-03

* Fixed standalone exclamation mark leading to endless loop - (@thekid)
* Fixed issue #3: Support images inside links - (@thekid)
* Fixed issue #1: Error message not helpful for unclosed tags - (@thekid)
* Fixed issue #4: Underlined headers appear in paragraphs - (@thekid)

## 0.9.0 / 2013-06-17

* Initial release with support for ordered and unordered lists, code 
  blocks, quotes, bold and italic, headers with Atx and Setex-style
  notations, horinzontal rulers, links and images - (@thekid)
