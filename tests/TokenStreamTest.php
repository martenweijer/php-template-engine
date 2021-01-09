<?php

use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;
use PHPUnit\Framework\TestCase;

class TokenStreamTest extends TestCase
{
    protected static $tokens;

    protected function setUp(): void
    {
        self::$tokens = [
            new Token(Token::TEXT, 'Hello '),
            new Token(Token::TEXT, 'world'),
            new Token(Token::TEXT, '!'),
            new Token(Token::EOF)
        ];
    }

    public function testNext(): void
    {
        $tokenStream = new TokenStream(self::$tokens);
        $list = [];
        while (!$tokenStream->isEof()) {
            $list[] = $tokenStream->getCurrentToken()->getType();
            $tokenStream->incrementIndex();
        }

        $this->assertEquals([
            Token::TEXT, Token::TEXT, Token::TEXT
        ], $list);
    }

    public function testException(): void
    {
        $tokenStream = new TokenStream();
        $this->expectException(RuntimeException::class);
        $tokenStream->getNextToken();
    }
}