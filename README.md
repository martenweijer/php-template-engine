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
```
@(if user.isAuthenticated)
    User is authenticated
@(elseif user.isSpecial)
    User is special
@(else)
    User is not authenticated
@(endif)
```
#### For loops
```
@(for user in users)
    email: @user.email
@(endfor)
```
#### Escaping
By default every variable is escaped
```php
echo $engine->render('@script', ['script' => '<script>alert("hello");</script>']); // &lt;script&gt;alert(&quot;hello&quot;);&lt;/script&gt;
```
#### Methods
```
@raw(script)
```
#### Multiple template directories
```php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Electronics\TemplateEngine\Loader\FilesystemLoader(__DIR__);
$loader->addNamespace('users', __DIR__ .'/users');
$engine->render('users::details.html', ['user' => $user]);
```
### Extending the engine
You can extend the engine by creating your own Extension implementation
```php
interface Extension
{
    function getParsers(): array;
    function getMethods(): array;
}
```