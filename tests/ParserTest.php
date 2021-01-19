<?php

use Electronics\TemplateEngine\Node\BlockNode;
use Electronics\TemplateEngine\Node\ClassNode;
use Electronics\TemplateEngine\Node\EchoNode;
use Electronics\TemplateEngine\Node\MethodNode;
use Electronics\TemplateEngine\Node\TextNode;
use Electronics\TemplateEngine\Node\VariableNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    protected Parser\ParserCollection $parserCollection;

    protected function setUp(): void
    {
        $this->parserCollection = new Parser\ParserCollection();
        $this->parserCollection->addParser(new Parser\IfTokenParser());
        $this->parserCollection->addParser(new Parser\ForTokenParser());
    }

    public function testRun(): void
    {
        $blockStack = new Parser\BlockStack();
        $blockStack->addNode(new EchoNode(new TextNode('foo')));

        $node = Parser::parse(new TokenStream([
            new Token(Token::TEXT, 'foo'),
            new Token(Token::EOF)
        ]), 'ParserTemplate', $this->parserCollection);
        $this->assertEquals(new ClassNode('ParserTemplate', $blockStack), $node);
    }

    public function testException(): void
    {
        $this->expectException(RuntimeException::class);
        Parser::parse(new TokenStream([
            new Token('foo')
        ]), '', $this->parserCollection);
    }

    public function testMethod(): void
    {
        $blockStack = new Parser\BlockStack();
        $blockStack->addNode(new MethodNode('raw', [new VariableNode('number')]));

        $node = Parser::parse(new TokenStream([
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'raw'),
            new Token(Token::NAME, 'number'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::EOF)
        ]), 'ParserTemplate', $this->parserCollection);
        $this->assertEquals(new ClassNode('ParserTemplate', $blockStack), $node);
    }
}