<?php

namespace Electronics\TemplateEngine\Node;

class EndNode implements Node
{
    public function write(Writer $writer): void
    {
        $writer->decreaseIndentation()
            ->write('}')
            ->newLine();
    }
}