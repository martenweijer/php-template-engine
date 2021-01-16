<?php

namespace Electronics\TemplateEngine\Extension;

use Electronics\TemplateEngine\Engine;
use Electronics\TemplateEngine\Parser\ForTokenParser;
use Electronics\TemplateEngine\Parser\IfTokenParser;
use Electronics\TemplateEngine\Parser\IncludeTokenParser;

class CoreExtension implements Extension
{
    protected Engine $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function getParsers(): array
    {
        return [
            new IfTokenParser(),
            new ForTokenParser(),
            new IncludeTokenParser()
        ];
    }

    public function getMethods(): array
    {
        return [
            'raw' => [$this, 'raw']
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function raw(mixed $_): void
    {
        echo $_;
    }
}