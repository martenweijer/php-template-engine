<?php

namespace Electronics\TemplateEngine\Extension;

use Electronics\TemplateEngine\Parser\ForTokenParser;
use Electronics\TemplateEngine\Parser\IfTokenParser;

class CoreExtension implements Extension
{
    public function getParsers(): array
    {
        return [
            new IfTokenParser(),
            new ForTokenParser()
        ];
    }
}