PHP Template Engine
======
[![Latest Version](https://img.shields.io/github/tag/martenweijer/php-template-engine.svg?style=flat-square)](https://github.com/martenweijer/php-template-engine/tags)

A template engine build in php.
## Requirements
The latest version of this package supports the following versions of PHP:
* PHP 8.0
## Usage
```php
require_once __DIR__ . '/vendor/autoload.php';

$engine = new \Electronics\TemplateEngine\Engine();
echo $engine->render('Hello @name!', ['name' => 'foo']); // Hello foo!
```