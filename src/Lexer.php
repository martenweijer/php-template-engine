<?php

namespace Electronics\TemplateEngine;

class Lexer
{
    protected string $string;
    protected TokenStream $tokenStream;

    protected function __construct(string $string)
    {
        $this->string = $string;
        $this->tokenStream = new TokenStream();
    }

    public static function tokenize(string $string): TokenStream
    {
        $lexer = new Lexer($string);
        $lexer->run();
        return $lexer->getTokenStream();
    }

    public function getTokenStream(): TokenStream
    {
        return $this->tokenStream;
    }

    public function run(): void
    {
        $this->tokenStream->addToken(new Token(Token::TEXT, $this->string));
        $this->tokenStream->addToken(new Token(Token::EOF));
    }
}