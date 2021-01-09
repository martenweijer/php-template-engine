<?php

use Electronics\TemplateEngine\Engine;
use PHPUnit\Framework\TestCase;

class EngineTest extends TestCase
{
    public function testCompile()
    {
        $engine = new Engine();
        $result = $engine->compile('foo');
        $this->assertEquals('<?php

class Template_2c26b46b68ffc68ff99b453c1d30413413422d706483bfa0f98a5e886266e7ae extends \Electronics\TemplateEngine\Template
{

    public function display(array $context): void
    {
        echo \'foo\';
    }
}
', $result);
    }
}