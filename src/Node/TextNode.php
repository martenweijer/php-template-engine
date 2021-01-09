<?php

namespace Electronics\TemplateEngine\Node;

class TextNode implements Node
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function write(Writer $writer): void
    {
        $writer->write('echo ')
            ->writeRaw('\''. str_replace('\\"', '"', addslashes($this->text)). '\'')
            ->writeRaw(';')
            ->newLine()
        ;
    }
}