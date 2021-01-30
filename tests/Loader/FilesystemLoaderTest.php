<?php

use Electronics\TemplateEngine\Loader\FilesystemLoader;
use PHPUnit\Framework\TestCase;

class FilesystemLoaderTest extends TestCase
{
    public function testGetContents(): void
    {
        $loader = new FilesystemLoader(__DIR__);
        $this->assertEquals('Hello @name!', $loader->getContents('test.html'));

        $this->expectException(RuntimeException::class);
        $loader->getContents('bar');
    }

    public function testAddNamespace(): void
    {
        $loader = new FilesystemLoader(__DIR__);
        $loader->addNamespace('foo', __DIR__ .'/foo');
        $this->assertEquals('bar', $loader->getContents('foo::bar.html'));
    }

    public function testCache(): void
    {
        $loader = new FilesystemLoader(__DIR__, __DIR__ .'/cache');
        $this->assertTrue($loader->isCacheEnabled());
        $this->assertFalse($loader->isFresh('test.html'));

        $loader->addToCache('test.html', 'test');
        $this->assertTrue($loader->isFresh('test.html'));

        $this->assertEquals('test', file_get_contents(__DIR__ .'/cache/test.html.php'));
    }

    protected function tearDown(): void
    {
        if (file_exists($file = __DIR__ .'/cache/test.html.php')) {
            unlink($file);
        }
    }
}