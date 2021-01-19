<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\ShowNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class ShowTokenParser implements TokenParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        $parser->addNode(new ShowNode(BlockStack::convertBlockName($tokenStream->getNextToken()->getValue())));
        $tokenStream->expect(Token::STRING);

        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::EXPR_END);
    }

    public function getIdentifiers(): array
    {
        return ['show'];
    }
}