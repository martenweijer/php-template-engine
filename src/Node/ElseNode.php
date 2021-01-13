<?php

namespace Electronics\TemplateEngine\Node;

class ElseNode implements Node
{
    public function write(Writer $writer): void
    {
        $writer->decreaseIndentation()
            ->write('}')
            ->newLine()
            ->write('else {')
            ->newLine()
            ->increaseIndentation();
    }
}