<?php

namespace Electronics\TemplateEngine\Node;

class MethodNode implements Node
{
    protected string $name;

    /** @var Node[] */
    protected array $arguments;

    public function __construct(string $name, array $arguments)
    {
        $this->name = $name;
        /** @var Node[] arguments */
        $this->arguments = $arguments;
    }

    public function write(Writer $writer): void
    {
        $writer->write('$this->callMethod(\''. $this->name .'\', [');

        foreach ($this->arguments as $argument) {
            $argument->write($writer);
        }

        $writer->writeRaw(']);')
            ->newLine();
    }
}