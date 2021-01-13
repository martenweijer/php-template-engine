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
            new Token(Token::NAME, 'name'),
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
            Token::TEXT, Token::NAME, Token::TEXT
        ], $list);
    }

    public function testException(): void
    {
        $tokenStream = new TokenStream();
        $this->expectException(RuntimeException::class);
        $tokenStream->getNextToken();
    }

    public function testExpect(): void
    {
        $tokenStream = new TokenStream(self::$tokens);
        $tokenStream->expect(Token::TEXT);

        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::NAME);

        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::TEXT);

        $this->expectException(RuntimeException::class);
        $tokenStream->incrementIndex();
        $tokenStream->expect(Token::NAME);
    }
}