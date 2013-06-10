Markdown for XP Framework
=========================
The [Markdown syntax](http://daringfireball.net/projects/markdown/syntax) implemented for the XP Framework.

```php
$engine= new \net\daringfireball\markdown\Markdown();
$transformed= $engine->transform(
  'This is [Markdown](http://daringfireball.net/projects/markdown/) for **XP**'
);
```