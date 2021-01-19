<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\MethodNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\StringNode;
use Electronics\TemplateEngine\Node\ValueNode;
use Electronics\TemplateEngine\Node\VariableNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;

class MethodParser
{
    public function parse(TokenStream $tokenStream, Parser $parser): void
    {
        $methodName = $tokenStream->getCurrentToken()->getValue();
        $tokenStream->incrementIndex();

        $nodes = [];
        while (!$tokenStream->getCurrentToken()->is(Token::EXPR_END)) {
            $nodes[] = $parser->processElement();

            if (!$tokenStream->getNextToken()->is(Token::EXPR_END)) {
                $tokenStream->expectValue(Token::PUNCTUATION, ',');
                $nodes[] = new ValueNode(', ');

                $tokenStream->incrementIndex();
            }
        }

        $parser->addNode(new MethodNode($methodName, $nodes));
    }
}