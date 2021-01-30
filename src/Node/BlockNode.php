<?php

namespace Electronics\TemplateEngine\Node;

class BlockNode implements Node
{
    protected string $block;

    /** @var Node[] */
    protected array $nodes = [];

    public function __construct(string $block)
    {
        $this->block = $block;
    }

    public function addNode(Node $node): void
    {
        $this->nodes[] = $node;
    }

    public function write(Writer $writer): void
    {
        $writer->newLine()
            ->write('public function '. $this->block .'(array $context, array $blocks): void')
            ->newLine()
            ->write('{')
            ->newLine()
            ->increaseIndentation()
        ;

        foreach ($this->nodes as $node) {
            $node->write($writer);
        }

        $writer->decreaseIndentation()
            ->write('}')
            ->newLine()
        ;
    }
}