<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Loader\Loader;
use Electronics\TemplateEngine\Loader\StringLoader;
use Electronics\TemplateEngine\Node\Writer;

class Engine
{
    protected Loader $loader;
    protected array $templates = [];

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
            $this->templates[$className] = $this->createTemplate($className);
        }

        /** @var Template $templateClass */
        $templateClass = $this->templates[$className];
        return $templateClass;
    }

    public function compile(string $template): string
    {
        $tokenStream = Lexer::tokenize($this->loader->getContents($template));
        $node = Parser::parse($tokenStream, $this->generateTemplateClassName($template));
        $node->write($writer = new Writer());
        return $writer->getSource();
    }

    public function escapeString(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    protected function generateTemplateClassName(string $template): string
    {
        return 'Template_'. hash('sha256', $template);
    }

    protected function createTemplate(string $className): Template
    {
        /** @var Template $templateClass */
        $templateClass = new $className($this);
        return $templateClass;
    }
}