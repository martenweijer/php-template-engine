<?php

namespace Electronics\TemplateEngine\Node;

class Writer
{
    protected $source = '';
    protected $indentation = 0;

    public function write(string $text): Writer
    {
        $this->source .= str_repeat('    ', $this->indentation) . $text;
        return $this;
    }

    public function writeRaw(string $text): Writer
    {
        $this->source .= $text;
        return $this;
    }

    public function newLine(): Writer
    {
        $this->source .= '
';
        return $this;
    }

    public function increaseIndentation(): Writer
    {
        $this->indentation++;
        return $this;
    }

    public function decreaseIndentation(): Writer
    {
        if ($this->indentation == 0) {
            throw new \InvalidArgumentException('Unable to decrease indendation as the indendation is already at 0.');
        }

        $this->indentation--;
        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}