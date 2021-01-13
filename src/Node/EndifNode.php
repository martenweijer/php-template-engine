<?php

namespace Electronics\TemplateEngine\Node;

class EndifNode implements Node
{
    public function write(Writer $writer): void
    {
        $writer->decreaseIndentation()
            ->write('}')
            ->newLine();
    }
}