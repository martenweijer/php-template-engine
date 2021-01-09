<?php

use Electronics\TemplateEngine\Loader\StringLoader;
use PHPUnit\Framework\TestCase;

class StringLoaderTest extends TestCase
{
    public function testGetContents(): void
    {
        $stringLoader = new StringLoader();
        $this->assertEquals('foo', $stringLoader->getContents('foo'));
    }
}