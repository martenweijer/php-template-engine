<?php

namespace Electronics\TemplateEngine\Node;

class ValueNode implements Node
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function write(Writer $writer): void
    {
        $writer->writeRaw($this->value);
    }
}