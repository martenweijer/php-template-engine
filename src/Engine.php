<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Extension\CoreExtension;
use Electronics\TemplateEngine\Extension\Extension;
use Electronics\TemplateEngine\Loader\Loader;
use Electronics\TemplateEngine\Loader\StringLoader;
use Electronics\TemplateEngine\Node\Writer;
use Electronics\TemplateEngine\Parser\ParserCollection;
use Electronics\TemplateEngine\Parser\TokenParser;

class Engine
{
    protected Loader $loader;
    protected array $templates = [];

    protected array $extensions = [];
    protected ParserCollection $parserCollection;

    public function __construct(Loader $loader = null)
    {
        $this->loader = $loader ?: new StringLoader();

        $this->parserCollection = new ParserCollection();

        $this->registerExtension(new CoreExtension());
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
        $node = Parser::parse($tokenStream, $this->generateTemplateClassName($template), $this->parserCollection);
        $node->write($writer = new Writer());
        return $writer->getSource();
    }

    public function registerExtension(Extension $extension): void
    {
        $this->extensions[] = $extension;

        /** @var TokenParser $parser */
        foreach ($extension->getParsers() as $parser) {
            $this->parserCollection->addParser($parser);
        }
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