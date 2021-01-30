<?php

namespace Electronics\TemplateEngine\Node;

class ShowNode implements Node
{
    protected string $block;

    public function __construct(string $block)
    {
        $this->block = $block;
    }

    public function write(Writer $writer): void
    {
        $writer->write('$this->showBlock(\''. $this->block .'\', $context, $blocks);')
            ->newLine();
    }
}