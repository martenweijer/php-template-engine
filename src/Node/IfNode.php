<?php

namespace Electronics\TemplateEngine\Node;

class IfNode implements Node
{
    protected Node $node;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    public function write(Writer $writer): void
    {
        $writer->write('if (');

        $this->node->write($writer);

        $writer->writeRaw(') {')
            ->newLine()
            ->increaseIndentation();
    }
}