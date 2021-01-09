<?php

namespace Electronics\TemplateEngine;

class TokenStream
{
    protected array $tokens;
    protected $index = 0;

    public function __construct(array $tokens = [])
    {
        $this->tokens = $tokens;
    }

    public function addToken(Token $token): void
    {
        $this->tokens[] = $token;
    }

    public function getCurrentToken(): Token
    {
        if (!isset($this->tokens[$this->index])) {
            throw new \RuntimeException(sprintf('There is no token available at index "%d".', $this->index));
        }

        return $this->tokens[$this->index];
    }

    public function getNextToken(): Token
    {
        $this->incrementIndex();
        return $this->getCurrentToken();
    }

    public function incrementIndex(): void
    {
        $this->index++;
    }

    public function isEof(): bool
    {
        return $this->getCurrentToken()->is(Token::EOF);
    }
}