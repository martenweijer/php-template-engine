<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Node\ClassNode;
use Electronics\TemplateEngine\Node\EchoNode;
use Electronics\TemplateEngine\Node\ElseifNode;
use Electronics\TemplateEngine\Node\ElseNode;
use Electronics\TemplateEngine\Node\EndNode;
use Electronics\TemplateEngine\Node\ForNode;
use Electronics\TemplateEngine\Node\IfNode;
use Electronics\TemplateEngine\Node\MethodNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\TextNode;
use Electronics\TemplateEngine\Node\VariableAsStringNode;
use Electronics\TemplateEngine\Node\VariableNode;
use Electronics\TemplateEngine\Parser\ParserCollection;

class Parser
{
    protected TokenStream $tokenStream;
    protected string $className;
    protected ParserCollection $parserCollection;
    protected ClassNode $classNode;

    protected function __construct(TokenStream $tokenStream, string $className, ParserCollection $parserCollection)
    {
        $this->tokenStream = $tokenStream;
        $this->className = $className;
        $this->parserCollection = $parserCollection;
        $this->classNode = new ClassNode($this->className);
    }

    public static function parse(TokenStream $tokenStream, string $className, ParserCollection $parserCollection): Node
    {
        $parser = new Parser($tokenStream, $className, $parserCollection);
        return $parser->run();
    }

    public function run(): Node
    {
        while (!$this->tokenStream->isEof()) {
            $this->process();
            $this->tokenStream->incrementIndex();
        }

        return $this->classNode;
    }

    public function process(): void
    {
        $token = $this->tokenStream->getCurrentToken();
        switch ($token->getType()) {
            case Token::TEXT:
                $this->addNode(new EchoNode(new TextNode($token->getValue())));
                break;
            case Token::NAME:
                $this->addNode(new EchoNode(new VariableAsStringNode($token->getValue())));
                break;
            case Token::EXPR_START:
                $this->parseExpression();
                break;
            case Token::METHOD:
                $this->parseMethod();
                break;
            default:
                throw new \RuntimeException(sprintf('Unknown token of type "%s" found.', $token->getType()));
        }
    }

    public function addNode(Node $node): void
    {
        $this->classNode->addNode($node);
    }

    protected function parseMethod(): void
    {
        $token = $this->tokenStream->getCurrentToken();
        $this->tokenStream->incrementIndex();
        $this->tokenStream->expect(Token::EXPR_START);

        $this->addNode(new MethodNode($token->getValue(), new VariableNode($this->tokenStream->getNextToken()->getValue())));
        $this->tokenStream->expect(Token::NAME);

        $this->tokenStream->incrementIndex();
        $this->tokenStream->expect(Token::EXPR_END);
    }

    protected function parseExpression(): void
    {
        $token = $this->tokenStream->getNextToken();
        $this->tokenStream->expect(Token::NAME);

        $this->parserCollection->getParser($token->getValue())
            ->parse($this->tokenStream, $this);
    }
}