<?php

namespace Electronics\TemplateEngine\Node;

class MethodNode implements Node
{
    protected string $name;
    private Node $argument;

    public function __construct(string $name, Node $argument)
    {
        $this->name = $name;
        $this->argument = $argument;
    }

    public function write(Writer $writer): void
    {
        $writer->write('$this->callMethod(\''. $this->name .'\', [');
        $this->argument->write($writer);
        $writer->writeRaw(']);')
            ->newLine();
    }
}