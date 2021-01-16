<?php

namespace Electronics\TemplateEngine\Node;

class IncludeNode implements Node
{
    protected string $template;

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function write(Writer $writer): void
    {
        $writer->write('echo $this->engine->render(\''. $this->template .'\', $context);')
            ->newLine();
    }
}