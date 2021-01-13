<?php

namespace Electronics\TemplateEngine\Node;

class ForNode implements Node
{
    protected string $value;
    protected Node $variable;

    public function __construct(string $value, Node $variable)
    {
        $this->value = $value;
        $this->variable = $variable;
    }

    public function write(Writer $writer): void
    {
        $writer->write('foreach (');
        $this->variable->write($writer);
        $writer->writeRaw(' as $context[\'')
            ->writeRaw($this->value)
            ->writeRaw('\']) {')
            ->newLine()
            ->increaseIndentation();
    }
}