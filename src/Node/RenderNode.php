<?php

namespace Electronics\TemplateEngine\Node;

class RenderNode implements Node
{
    protected string $template;

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function write(Writer $writer): void
    {
        $writer->write('echo $this->engine->load(\''. $this->template .'\')->render($context, $blocks);')
            ->newLine();
    }
}