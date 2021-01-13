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
#### Load template from filesystem
```php
require_once __DIR__ . '/vendor/autoload.php';

$engine = new \Electronics\TemplateEngine\Engine(
    new \Electronics\TemplateEngine\Loader\FilesystemLoader(__DIR__)
);
echo $engine->render('template.html');
```
#### Conditional statements
```php
@(if user.isAuthenticated)
    User is authenticated
@(elseif user.isSpecial)
    User is special
@(else)
    User is not authenticated
@(endif)
```