<?php

namespace Electronics\TemplateEngine\Node;

class VariableNode implements Node
{
    protected string $variable;

    public function __construct(string $variable)
    {
        $this->variable = $variable;
    }

    public function write(Writer $writer): void
    {
        $writer->writeRaw(sprintf('$this->getVariable(\'%s\', $context)', $this->variable));
    }
}