Markdown for XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

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
