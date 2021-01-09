<?php

use Electronics\TemplateEngine\Node\VariableAsStringNode;
use Electronics\TemplateEngine\Node\Writer;
use PHPUnit\Framework\TestCase;

class VariableAsStringNodeTest extends TestCase
{
    public function testWrite(): void
    {
        $node = new VariableAsStringNode('foo');
        $node->write($writer = new Writer());
        $this->assertEquals('$this->getVariableAsString(\'foo\', $context)', $writer->getSource());
    }
}