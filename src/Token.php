<?php

namespace Electronics\TemplateEngine;

class Token
{
    const TEXT = 'text';
    const EOF = 'eof';

    protected string $type;
    protected string $value;

    public function __construct(string $type, string $value = '')
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function is(string $type): bool
    {
        return $this->type == $type;
    }
}