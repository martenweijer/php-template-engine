<?php

use Electronics\TemplateEngine\Engine;
use Electronics\TemplateEngine\Loader\FilesystemLoader;
use PHPUnit\Framework\TestCase;

class EngineTest extends TestCase
{
    public function testCompile(): void
    {
        $engine = new Engine();
        $result = $engine->compile('foo');
        $this->assertEquals('<?php

class Template_2c26b46b68ffc68ff99b453c1d30413413422d706483bfa0f98a5e886266e7ae extends \Electronics\TemplateEngine\Template
{
    public function __construct(\Electronics\TemplateEngine\Engine $engine)
    {
        parent::__construct($engine);
        $this->blocks[\'display\'] = [$this, \'display\'];
    }

    public function display(array $context, array $blocks): void
    {
        echo \'foo\';
    }
}
', $result);
    }

    public function testVariablesCompiled(): void
    {
        $engine = new Engine();
        $result = $engine->compile('Hello @name!');
        $this->assertEquals('<?php

class Template_22bea0295217a93e83e03d0610000431e3109f05c88eed90333c00f8dc20ad38 extends \Electronics\TemplateEngine\Template
{
    public function __construct(\Electronics\TemplateEngine\Engine $engine)
    {
        parent::__construct($engine);
        $this->blocks[\'display\'] = [$this, \'display\'];
    }

    public function display(array $context, array $blocks): void
    {
        echo \'Hello \';
        echo $this->getVariableAsString(\'name\', $context);
        echo \'!\';
    }
}
', $result);
    }

    public function testVariablesResult(): void
    {
        $engine = new Engine();
        $result = $engine->render('Hello @name!', ['name' => 'foo']);
        $this->assertEquals('Hello foo!', $result);

        $engine = new Engine();
        $result = $engine->render('Hello @foo@bar!', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals('Hello barfoo!', $result);

        $engine = new Engine();
        $result = $engine->render('Hello @foo @bar!', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertEquals('Hello bar foo!', $result);

        $engine = new Engine();
        $result = $engine->render('Hello @user.email!', ['user' => ['email' => 'test@test.com']]);
        $this->assertEquals('Hello test@test.com!', $result);
    }

    public function testRender(): void
    {
        $engine = new Engine();
        $this->assertEquals('foo bar', $engine->render('@foo @bar', ['foo' => 'foo', 'bar' => 'bar']));
    }

    public function testStatements(): void
    {
        $engine = new Engine();
        $this->assertEquals('foo',
            $engine->render('@(if bool)foo@(else)bar@(endif)', ['bool' => true]));
    }

    public function testLoops(): void
    {
        $engine = new Engine();
        $this->assertEquals('123',
            $engine->render('@(for e in number)@e@(endfor)', ['number' => [1, 2, 3]]));
    }

    public function testInheritance()
    {
        $engine = new Engine(new FilesystemLoader(__DIR__ .'/templates'));
        $this->assertEquals('Hello bar', $engine->render('foo.html'));
        $this->assertEquals('Hello from layout!Hello from app.html', $engine->render('app.html'));
        $this->assertEquals('Hello from layout!Hello bar', $engine->render('app2.html'));
    }
}