Markdown for XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

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
