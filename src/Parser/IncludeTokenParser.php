<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\RenderNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class IncludeTokenParser implements TokenParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::STRING);
        $parser->addNode(new RenderNode($tokenStream->getCurrentToken()->getValue()));

        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::EXPR_END);
    }

    public function getIdentifiers(): array
    {
        return ['include'];
    }
}