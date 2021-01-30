<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Node\ClassNode;
use Electronics\TemplateEngine\Node\EchoNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\StringNode;
use Electronics\TemplateEngine\Node\TextNode;
use Electronics\TemplateEngine\Node\VariableAsStringNode;
use Electronics\TemplateEngine\Node\VariableNode;
use Electronics\TemplateEngine\Parser\BlockStack;
use Electronics\TemplateEngine\Parser\ParserCollection;

class Parser
{
    protected TokenStream $tokenStream;
    protected string $className;
    protected ParserCollection $parserCollection;
    protected BlockStack $blockStack;

    protected function __construct(TokenStream $tokenStream, string $className, ParserCollection $parserCollection)
    {
        $this->tokenStream = $tokenStream;
        $this->className = $className;
        $this->parserCollection = $parserCollection;

        $this->blockStack = new BlockStack();
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

        return new ClassNode($this->className, $this->blockStack);
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
            default:
                throw new \RuntimeException(sprintf('Unknown token of type "%s" found.', $token->getType()));
        }
    }

    public function processElement(): Node
    {
        $token = $this->tokenStream->getCurrentToken();
        switch ($token->getType()) {
            case Token::NAME:
                return new VariableNode($token->getValue());
            case Token::STRING:
                return new StringNode($token->getValue());
        }

        throw new \RuntimeException(sprintf('Unknown token of type "%s" found.', $token->getType()));
    }

    public function addNode(Node $node): void
    {
        $this->blockStack->addNode($node);
    }

    public function startBlock(string $block): void
    {
        $this->blockStack->startBlock($block);
    }

    public function stopBlock(): void
    {
        $this->blockStack->stopBlock();
    }

    protected function parseExpression(): void
    {
        $token = $this->tokenStream->getNextToken();
        $this->tokenStream->expect(Token::NAME);

        if ($this->parserCollection->hasParser($token->getValue())) {
            $this->parserCollection->getParser($token->getValue())
                ->parse($this->tokenStream, $this);
        }

        else {
            $this->parserCollection->getDedicatedMethodParser()
                ->parse($this->tokenStream, $this);
        }
    }
}