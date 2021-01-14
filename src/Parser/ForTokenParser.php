<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\EndNode;
use Electronics\TemplateEngine\Node\ForNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\VariableNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class ForTokenParser implements TokenParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        $token = $tokenStream->getCurrentToken();
        if ($token->getValue() == 'for') {
            $value = $tokenStream->getNextToken()->getValue();
            $tokenStream->expect(Token::NAME);

            $tokenStream->incrementIndex();
            $tokenStream->expectValue(Token::NAME, 'in');

            $token = $tokenStream->getNextToken();
            $tokenStream->expect(Token::NAME);

            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);

            $parser->addNode(new ForNode($value, new VariableNode($token->getValue())));
        }

        else if ($token->getValue() == 'endfor') {
            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);

            $parser->addNode(new EndNode());
        }
    }

    public function getIdentifiers(): array
    {
        return ['for', 'endfor'];
    }
}