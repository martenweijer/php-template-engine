<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Loader\Loader;
use Electronics\TemplateEngine\Loader\StringLoader;
use Electronics\TemplateEngine\Node\Writer;

class Engine
{
    protected Loader $loader;
    protected $templates = [];

    public function __construct(Loader $loader = null)
    {
        $this->loader = $loader ?: new StringLoader();
    }

    public function render(string $template, array $context = []): string
    {
        return $this->load($template)->render($context);
    }

    public function load(string $template): Template
    {
        $className = $this->generateTemplateClassName($template);

        if (!isset($this->templates[$className])) {
            eval('?>'. $this->compile($template));
            $this->templates[$className] = new $className;
        }

        return $this->templates[$className];
    }

    public function compile($template)
    {
        $tokenStream = Lexer::tokenize($this->loader->getContents($template));
        $node = Parser::parse($tokenStream, $this->generateTemplateClassName($template));
        $node->write($writer = new Writer());
        return $writer->getSource();
    }

    protected function generateTemplateClassName(string $template): string
    {
        return 'Template_'. hash('sha256', $template);
    }
}