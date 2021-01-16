<?php

use Electronics\TemplateEngine\Loader\FilesystemLoader;
use PHPUnit\Framework\TestCase;

class FilesystemLoaderTest extends TestCase
{
    public function testGetContents(): void
    {
        $loader = new FilesystemLoader(__DIR__);
        $this->assertEquals('Hello @name!', $loader->getContents('filesystemloadertest.template'));

        $this->expectException(RuntimeException::class);
        $loader->getContents('bar');
    }

    public function testAddNamespace(): void
    {
        $loader = new FilesystemLoader(__DIR__);
        $loader->addNamespace('foo', __DIR__ .'/foo');
        $this->assertEquals('bar', $loader->getContents('foo::bar.html'));
    }
}