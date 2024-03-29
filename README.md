Markdown for XP Framework
=========================

[![Build status on GitHub](https://github.com/xp-forge/markdown/workflows/Tests/badge.svg)](https://github.com/xp-forge/markdown/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
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

$tree= $engine->parse($markdown);
$tree= $engine->parse(new TextReader(new File('file.md')));

// ...work with tree...

$transformed= $tree->emit(new ToHtml());
```

You can control the URLs used in the `href` and `src` attributes of links and images, respectively, by using URL rewriting API:

```php
use net\daringfireball\markdown\{ToHtml, URLs, Rewriting};

$emitter= new ToHtml(new URLs(Rewriting::absolute()
  ->links('/deref?url=%s')
  ->images('/proxy?url=&s')
  ->excluding(['localhost'])
));

$transformed= $engine->transform($markdown, [], $emitter);
$transformed= $engine->parse($markdown)->emit($emitter);
```