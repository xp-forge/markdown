Markdown for XP Framework
=========================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/markdown.svg)](http://travis-ci.org/xp-forge/markdown)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/markdown/version.png)](https://packagist.org/packages/xp-forge/markdown)

The [Markdown syntax](http://daringfireball.net/projects/markdown/syntax) implemented for the XP Framework.

Example
-------
To transform markdown to HTML, all that is necessary is the following:

```php
use net\daringfireball\markdown\Markdown;

$engine= new Markdown();
$transformed= $engine->transform(
  'This is [Markdown](http://daringfireball.net/projects/markdown/) for **XP**'
);
```

The implementation is based on a parse tree. To work with the tree, you can use the `parse()` method, which returns a `net.daringfireball.markdown.ParseTree` instance.

```php
use net\daringfireball\markdown\{Markdown, ToHtml};
use io\streams\TextReader;
use io\File;

$engine= new Markdown();
$tree= $engine->parse(new TextReader(new File('file.md')));

// ...work with tree...

$tree->emit(new ToHtml());
```