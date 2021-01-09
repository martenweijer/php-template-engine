<?php

namespace Electronics\TemplateEngine;

use Electronics\TemplateEngine\Node\ClassNode;
use Electronics\TemplateEngine\Node\Node;
use Electronics\TemplateEngine\Node\TextNode;

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
                    $classNode->addNode(new TextNode($token->getValue()));
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unknown token of type "%s" found.', $token->getType()));
            }

            $this->tokenStream->incrementIndex();
        }

        return $classNode;
    }
}