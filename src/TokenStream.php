<?php

namespace Electronics\TemplateEngine;

class TokenStream
{
    protected array $tokens;
    protected int $index = 0;

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

        /** @var Token $token */
        $token = $this->tokens[$this->index];
        return $token;
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

    public function expect(string ...$types): void
    {
        $token = $this->getCurrentToken();
        foreach ($types as $type) {
            if ($token->getType() == $type) {
                return;
            }
        }

        throw new \RuntimeException(sprintf('Expected a token of type "%s", got "%s".', implode(';', $types), $token->getType()));
    }

    public function expectValue(string $type, mixed $value): void
    {
        $token = $this->getCurrentToken();
        if ($token->getType() == $type && $token->getValue() == $value) {
            return;
        }

        throw new \RuntimeException(sprintf('Expected a token of type "%s" with value "%s", got "%s" "%s".',
            $type, (string) $value,
            $token->getType(), $token->getValue()));
    }
}