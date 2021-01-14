<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\TokenStream;

interface TokenParser
{
    /**
     * @return string[]
     */
    function getIdentifiers(): array;
    function parse(TokenStream $tokenStream, Parser $parser): void;
}