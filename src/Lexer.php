<?php

namespace Electronics\TemplateEngine;

class Lexer
{
    const REGEX_NAME = '/[a-zA-Z_][a-zA-Z0-9_.]*/A';
    const REGEX_METHOD = '/([a-zA-Z_]+)(\()/A';
    const REGEX_EXPR_START = '/\(/A';
    const REGEX_EXPR_END = '/\s*\)/A';
    const REGEX_STRING = '/"([^#"\\\\]*(?:\\\\.[^#"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'/As';
    const REGEX_PUNCTUATION = '/,|\?|:|<=|<|==|>=|>|=>|=/A';
    const REGEX_WHITESPACE = '/\s+/A';

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

        $this->addToken(Token::TEXT, substr($this->string, $this->cursorIndex));
        $this->tokenStream->addToken(new Token(Token::EOF));
    }

    protected function processStep(): void
    {
        if (preg_match(Lexer::REGEX_METHOD, $this->string, $match, 0, $this->cursorIndex)) {
            $this->addToken(Token::EXPR_START, $match[2]);
            $this->addToken(Token::NAME, $match[1]);
            $this->moveCursor($match[0]);
            $this->processExpression();
            return;
        }

        if (preg_match(Lexer::REGEX_NAME, $this->string, $match, 0, $this->cursorIndex)) {
            $this->addToken(Token::NAME, $match[0]);
            $this->moveCursor($match[0]);
            return;
        }

        if (preg_match(Lexer::REGEX_EXPR_START, $this->string, $match, 0, $this->cursorIndex)) {
            $this->addToken(Token::EXPR_START, $match[0]);
            $this->moveCursor($match[0]);
            $this->processExpression();
            return;
        }

        throw new \RuntimeException(sprintf('Invalid character "%s" found.', $this->string[$this->cursorIndex]));
    }

    protected function processExpression(): void
    {
        $matches = [];
        while (!preg_match(Lexer::REGEX_EXPR_END, $this->string, $matches, 0, $this->cursorIndex)) {
            $this->ignoreWhitespace();

            if (preg_match(Lexer::REGEX_NAME, $this->string, $match, 0, $this->cursorIndex)) {
                $this->addToken(Token::NAME, $match[0]);
                $this->moveCursor($match[0]);
            } else if (preg_match(Lexer::REGEX_STRING, $this->string, $match, 0, $this->cursorIndex)) {
                $this->addToken(Token::STRING, stripslashes(substr($match[0], 1, -1)));
                $this->moveCursor($match[0]);
            } else if (preg_match(Lexer::REGEX_PUNCTUATION, $this->string, $match, 0, $this->cursorIndex)) {
                $this->addToken(Token::PUNCTUATION, $match[0]);
                $this->moveCursor($match[0]);
            } else {
                throw new \RuntimeException(sprintf('Invalid character "%s" found.', $this->string[$this->cursorIndex]));
            }
        }

        $this->addToken(Token::EXPR_END, $matches[0]);
        $this->moveCursor($matches[0]);
    }

    protected function ignoreWhitespace(): void
    {
        if (preg_match(Lexer::REGEX_WHITESPACE, $this->string, $match, 0, $this->cursorIndex)) {
            $this->moveCursor($match[0]);
        }
    }

    protected function moveCursor(string $text): void
    {
        $this->cursorIndex += strlen($text);
    }

    protected function addToken(string $type, string $value = ''): void
    {
        if ($type == Token::TEXT && empty(trim($value)) && $value != ' ') {
            return;
        }

        $this->tokenStream->addToken(new Token($type, $value));
    }
}