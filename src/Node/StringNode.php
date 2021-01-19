<?php

namespace Electronics\TemplateEngine\Node;

class StringNode implements Node
{
    protected string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function write(Writer $writer): void
    {
        $writer->writeRaw('\''. $this->string .'\'');
    }
}