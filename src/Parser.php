<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Node\ClassNode;
use Electronics\TemplateEngine\Node\EchoNode;
use Electronics\TemplateEngine\Node\ElseifNode;
use Electronics\TemplateEngine\Node\ElseNode;
use Electronics\TemplateEngine\Node\EndifNode;
use Electronics\TemplateEngine\Node\IfNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\TextNode;
use Electronics\TemplateEngine\Node\VariableAsStringNode;
use Electronics\TemplateEngine\Node\VariableNode;

class Parser
{
    protected TokenStream $tokenStream;
    protected string $className;

    protected function __construct(TokenStream $tokenStream, string $className)
    {
        $this->tokenStream = $tokenStream;
        $this->className = $className;
    }

    public static function parse(TokenStream $tokenStream, string $className): Node
    {
        $parser = new Parser($tokenStream, $className);
        return $parser->run();
    }

    public function run(): Node
    {
        $classNode = new ClassNode($this->className);

        while (!$this->tokenStream->isEof()) {
            $token = $this->tokenStream->getCurrentToken();
            switch ($token->getType()) {
                case Token::TEXT:
                    $classNode->addNode(new EchoNode(new TextNode($token->getValue())));
                    break;
                case Token::VARIABLE:
                    $classNode->addNode(new EchoNode(new VariableAsStringNode($token->getValue())));
                    break;
                case Token::EXPR_START:
                    $classNode->addNode($this->parseExpression());
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unknown token of type "%s" found.', $token->getType()));
            }

            $this->tokenStream->incrementIndex();
        }

        return $classNode;
    }

    protected function parseExpression(): Node
    {
        $token = $this->tokenStream->getNextToken();
        $this->tokenStream->expect(Token::NAME);

        if ($token->getValue() == 'if') {
            $token = $this->tokenStream->getNextToken();
            $this->tokenStream->expect(Token::NAME, Token::VARIABLE);

            $this->tokenStream->incrementIndex();
            $this->tokenStream->expect(Token::EXPR_END);

            return new IfNode(new VariableNode($token->getValue()));
        }

        if ($token->getValue() == 'elseif') {
            $token = $this->tokenStream->getNextToken();
            $this->tokenStream->expect(Token::NAME, Token::VARIABLE);

            $this->tokenStream->incrementIndex();
            $this->tokenStream->expect(Token::EXPR_END);

            return new ElseifNode(new VariableNode($token->getValue()));
        }

        if ($token->getValue() == 'else') {
            $this->tokenStream->incrementIndex();
            $this->tokenStream->expect(Token::EXPR_END);

            return new ElseNode();
        }

        if ($token->getValue() == 'endif') {
            $this->tokenStream->incrementIndex();
            $this->tokenStream->expect(Token::EXPR_END);

            return new EndifNode();
        }

        throw new \RuntimeException(sprintf('Unknown expression "%s" found.', $token->getValue()));
    }
}