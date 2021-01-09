<?php

use Electronics\TemplateEngine\Node\TextNode;
use Electronics\TemplateEngine\Node\Writer;
use PHPUnit\Framework\TestCase;

class TextNodeTest extends TestCase
{
    public function testWrite(): void
    {
        $textNode = new TextNode('foo');
        $textNode->write($writer = new Writer());
        $this->assertEquals('echo \'foo\';
', $writer->getSource());

        $textNode = new TextNode('foo\'bar');
        $textNode->write($writer = new Writer());
        $this->assertEquals('echo \'foo\\\'bar\';
', $writer->getSource());
    }
}