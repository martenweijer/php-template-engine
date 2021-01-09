<?php

namespace Electronics\TemplateEngine\Node;

class VariableAsStringNode implements Node
{
    protected string $variable;

    public function __construct(string $variable)
    {
        $this->variable = $variable;
    }

    public function write(Writer $writer): void
    {
        $writer->writeRaw(sprintf('$this->getVariableAsString(\'%s\', $context)', $this->variable));
    }
}