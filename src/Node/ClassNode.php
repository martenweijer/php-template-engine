<?php

namespace Electronics\TemplateEngine\Node;

use Electronics\TemplateEngine\Parser\BlockStack;

class ClassNode implements Node
{
    protected string $className;
    protected BlockStack $blockStack;

    public function __construct(string $className, BlockStack $blockStack)
    {
        $this->className = $className;
        $this->blockStack = $blockStack;
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
            ->increaseIndentation()
        ;

        $writer->write('public function __construct(\Electronics\TemplateEngine\Engine $engine)')
            ->newLine()
            ->write('{')
            ->newLine()
            ->increaseIndentation()
            ->write('parent::__construct($engine);')
            ->newLine();

        foreach ($this->blockStack->getBlockNames() as $block) {
            $writer->write('$this->blocks[\''. $block .'\'] = [$this, \''. $block .'\'];')
                ->newLine();
        }

        $writer->decreaseIndentation()
            ->write('}')
            ->newLine()
        ;

        foreach ($this->blockStack->getNodes() as $node) {
            $node->write($writer);
        }

        $writer->decreaseIndentation()
            ->write('}')
            ->newLine()
        ;
    }
}