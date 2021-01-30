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

        $tokenStream = Lexer::tokenize('Hello @foo');
        $this->assertEquals(new TokenStream([
            new Token(Token::TEXT, 'Hello '),
            new Token(Token::NAME, 'foo'),
            new Token(Token::EOF)
        ]), $tokenStream);
    }

    public function testStatement(): void
    {
        $tokenStream = Lexer::tokenize('@(if bool)hello@(endif)');
        $this->assertEquals(new TokenStream([
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'if'),
            new Token(Token::NAME, 'bool'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::TEXT, 'hello'),
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'endif'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::EOF)
        ]), $tokenStream);

        $tokenStream = Lexer::tokenize('@(if bool)hello@(elseif bool2)foo@(else)bar@(endif)');
        $this->assertEquals(new TokenStream([
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'if'),
            new Token(Token::NAME, 'bool'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::TEXT, 'hello'),
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'elseif'),
            new Token(Token::NAME, 'bool2'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::TEXT, 'foo'),
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'else'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::TEXT, 'bar'),
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'endif'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::EOF)
        ]), $tokenStream);
    }

    public function testLoops(): void
    {
        $tokenStream = Lexer::tokenize('@(for e in number)@e@(endfor)');
        $this->assertEquals(new TokenStream([
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'for'),
            new Token(Token::NAME, 'e'),
            new Token(Token::NAME, 'in'),
            new Token(Token::NAME, 'number'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::NAME, 'e'),
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'endfor'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::EOF)
        ]), $tokenStream);
    }

    public function testMethods(): void
    {
        $tokenStream = Lexer::tokenize('@raw(number)');
        $this->assertEquals(new TokenStream([
            new Token(Token::EXPR_START, '('),
            new Token(Token::NAME, 'raw'),
            new Token(Token::NAME, 'number'),
            new Token(Token::EXPR_END, ')'),
            new Token(Token::EOF)
        ]), $tokenStream);
    }
}