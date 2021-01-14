<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\ElseifNode;
use Electronics\TemplateEngine\Node\ElseNode;
use Electronics\TemplateEngine\Node\EndNode;
use Electronics\TemplateEngine\Node\IfNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\VariableNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class IfTokenParser implements TokenParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        $token = $tokenStream->getCurrentToken();
        if ($token->getValue() == 'if') {
            $token = $tokenStream->getNextToken();
            $tokenStream->expect(Token::NAME);

            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);

            $parser->addNode(new IfNode(new VariableNode($token->getValue())));
        }

        else if ($token->getValue() == 'elseif') {
            $token = $tokenStream->getNextToken();
            $tokenStream->expect(Token::NAME);

            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);

            $parser->addNode(new ElseifNode(new VariableNode($token->getValue())));
        }

        else if ($token->getValue() == 'else') {
            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);

            $parser->addNode(new ElseNode());
        }

        else if ($token->getValue() == 'endif') {
            $tokenStream->incrementIndex();
            $tokenStream->expect(Token::EXPR_END);

            $parser->addNode(new EndNode());
        }
    }

    public function getIdentifiers(): array
    {
        return ['if', 'elseif', 'else', 'endif'];
    }
}