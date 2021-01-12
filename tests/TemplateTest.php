<?php

use Electronics\TemplateEngine\Engine;
use Electronics\TemplateEngine\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function testGetVariable(): void
    {
        $template = new TemplateTestTemplate(new Engine());

        $this->assertEquals('bar', $template->getVariable('foo', ['foo' => 'bar']));
        $this->assertEquals(null, $template->getVariable('bar', ['foo' => 'bar']));
        $this->assertEquals('test', $template->getVariable('foo.bar', ['foo' => ['bar' => 'test']]));
        $this->assertEquals('email', $template->getVariable('foo.bar.test', ['foo' => ['bar' => ['test' => 'email']]]));

        $obj = new stdClass();
        $obj->foo = 'bar';
        $this->assertEquals('bar', $template->getVariable('foo', $obj));

        $obj->foo = new stdClass();
        $obj->foo->bar = 'test';
        $this->assertEquals('test', $template->getVariable('foo.bar', $obj));

        $obj->foo->bar = new stdClass();
        $obj->foo->bar->test = 'email';
        $this->assertEquals('email', $template->getVariable('foo.bar.test', $obj));
    }
}

class TemplateTestTemplate extends Template
{
    public function display(array $context): void
    {
    }
}