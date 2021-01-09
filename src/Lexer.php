<?php

namespace Electronics\TemplateEngine;

class Lexer
{
    const REGEX_NAME = '/[a-zA-Z][a-zA-Z0-9._]*/A';

    protected string $string;
    protected TokenStream $tokenStream;
    protected int $cursorIndex = 0;

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
        preg_match_all('/@/s', $this->string, $matches, PREG_OFFSET_CAPTURE);
        $positions = count($matches[0]);

        $index = 0;
        while ($index < $positions) {
            list($value, $position) = $matches[0][$index++];

            if ($position < $this->cursorIndex) {
                continue;
            }

            $text = substr($this->string, $this->cursorIndex, $position - $this->cursorIndex);
            $this->addToken(Token::TEXT, $text);
            $this->moveCursor($text . $value);

            $this->processStep();
        }

        $this->tokenStream->addToken(new Token(Token::TEXT, substr($this->string, $this->cursorIndex)));
        $this->tokenStream->addToken(new Token(Token::EOF));
    }

    protected function processStep(): void
    {
        if (preg_match(Lexer::REGEX_NAME, $this->string, $match, 0, $this->cursorIndex)) {
            $this->addToken(Token::NAME, $match[0]);
            $this->moveCursor($match[0]);
            return;
        }

        throw new \RuntimeException(sprintf('Invalid character "%s" found.', $this->string[$this->cursorIndex]));
    }

    protected function moveCursor(string $text): void
    {
        $this->cursorIndex += strlen($text);
    }

    protected function addToken(string $type, string $value = ''): void
    {
        $this->tokenStream->addToken(new Token($type, $value));
    }
}