<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\RenderNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class ExtendsTokenParser implements TokenParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        if ($tokenStream->getIndex() != 1) {
            throw new \RuntimeException('@extends should be on the first line of your template.');
        }

        $token = $tokenStream->getNextToken();
        $tokenStream->expect(Token::STRING);

        $parser->addNode(new RenderNode($token->getValue()));

        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::EXPR_END);
    }

    public function getIdentifiers(): array
    {
        return ['extends'];
    }
}