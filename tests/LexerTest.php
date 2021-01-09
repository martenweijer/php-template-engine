<?php

use Electronics\TemplateEngine\Lexer;
use Electronics\TemplateEngine\Token;
use Electronics\TemplateEngine\TokenStream;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public function testTokenize(): void
    {
        $tokenStream = Lexer::tokenize('foo');
        $this->assertEquals(new TokenStream([
            new Token(Token::TEXT, 'foo'),
            new Token(Token::EOF)
        ]), $tokenStream);
    }
}