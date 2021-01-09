<?php

use Electronics\TemplateEngine\Node\ClassNode;
use Electronics\TemplateEngine\Node\EchoNode;
use Electronics\TemplateEngine\Node\TextNode;
use Electronics\TemplateEngine\Parser;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testRun(): void
    {
        $node = Parser::parse(new TokenStream([
            new Token(Token::TEXT, 'foo'),
            new Token(Token::EOF)
        ]), 'ParserTemplate');
        $this->assertEquals(new ClassNode('ParserTemplate', [new EchoNode(new TextNode('foo'))]), $node);
    }

    public function testException(): void
    {
        $this->expectException(RuntimeException::class);
        Parser::parse(new TokenStream([
            new Token('foo')
        ]), '');
    }
}