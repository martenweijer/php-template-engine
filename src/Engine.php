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

    /** @var array<string, Template> */
    protected array $templates = [];

    protected ParserCollection $parserCollection;

    /** @var array<string, callable> */
    protected $methods = [];

    public function __construct(Loader $loader = null)
    {
        $this->loader = $loader ?: new StringLoader();
        $this->parserCollection = new ParserCollection();

        $this->registerExtension(new CoreExtension($this));
    }

    public function setParserCollection(ParserCollection $parserCollection): void
    {
        $this->parserCollection = $parserCollection;
    }

    public function registerExtension(Extension $extension): void
    {
        /** @var TokenParser $parser */
        foreach ($extension->getParsers() as $parser) {
            $this->parserCollection->addParser($parser);
        }

        foreach ($extension->getMethods() as $method => $callable) {
            $this->methods[$method] = $callable;
        }
    }

    public function render(string $template, array $context = []): string
    {
        return $this->load($template)->render($context);
    }

    public function load(string $template): Template
    {
        $className = $this->generateTemplateClassName($template);

        if (!isset($this->templates[$className])) {
            $this->loadTemplate($template);

            /** @var Template $templateClass */
            $templateClass = new $className($this);
            $this->templates[$className] = $templateClass;
        }

        return $this->templates[$className];
    }

    public function compile(string $template): string
    {
        $tokenStream = Lexer::tokenize($this->loader->getContents($template));
        $node = Parser::parse($tokenStream, $this->generateTemplateClassName($template), $this->parserCollection);
        $node->write($writer = new Writer());
        return $writer->getSource();
    }

    public function getMethod(string $method): callable
    {
        if (!isset($this->methods[$method])) {
            throw new \InvalidArgumentException(sprintf('Method "%s" not registered.', $method));
        }

        return $this->methods[$method];
    }

    public function escapeString(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    protected function loadTemplate(string $template): void
    {
        if ($this->loader->isCacheEnabled()) {
            if (!$this->loader->isFresh($template)) {
                $this->loader->addToCache($template, $this->compile($template));
            }

            $this->loader->loadFromCache($template);
        } else {
            eval('?>'. $this->compile($template));
        }
    }

    protected function generateTemplateClassName(string $template): string
    {
        return 'Template_'. hash('sha256', $template);
    }
}