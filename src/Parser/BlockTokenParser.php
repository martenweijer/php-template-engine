<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class BlockTokenParser implements TokenParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        $token = $tokenStream->getCurrentToken();
        if ($token->getValue() == 'block') {
            $token = $tokenStream->getNextToken();
            $tokenStream->expect(Token::STRING);

            $parser->startBlock($token->getValue());

            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);
        }

        else if ($token->getValue() == 'endblock') {
            $parser->stopBlock();

            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);
        }
    }

    public function getIdentifiers(): array
    {
        return ['block', 'endblock'];
    }
}