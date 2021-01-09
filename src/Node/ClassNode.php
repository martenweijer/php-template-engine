<?php

namespace Electronics\TemplateEngine\Node;

class ClassNode implements Node
{
    protected string $className;
    protected array $nodes;

    public function __construct(string $className, array $nodes = [])
    {
        $this->className = $className;
        $this->nodes = $nodes;
    }

    public function addNode(Node $node): void
    {
        $this->nodes[] = $node;
    }

    public function write(Writer $writer): void
    {
        $writer->write('<?php')
            ->newLine()
            ->newLine()
            ->write(sprintf('class %s extends \\Electronics\\TemplateEngine\\Template', $this->className))
            ->newLine()
            ->write('{')
            ->newLine()
            ->newLine()
            ->increaseIndentation()
        ;

        $writer->write('public function display(array $context): void')
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

        $writer->decreaseIndentation()
            ->write('}')
            ->newLine()
        ;
    }
}