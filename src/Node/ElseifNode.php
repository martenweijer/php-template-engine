<?php

namespace Electronics\TemplateEngine\Node;

class ElseifNode implements Node
{
    protected Node $node;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    public function write(Writer $writer): void
    {
        $writer->decreaseIndentation()
            ->write('}')
            ->newLine()
            ->write('elseif (');

        $this->node->write($writer);

        $writer->writeRaw(') {')
            ->newLine()
            ->increaseIndentation();
    }
}