<?php

namespace Electronics\TemplateEngine\Extension;

use Electronics\TemplateEngine\Engine;
use Electronics\TemplateEngine\Parser\BlockTokenParser;
use Electronics\TemplateEngine\Parser\ExtendsTokenParser;
use Electronics\TemplateEngine\Parser\ForTokenParser;
use Electronics\TemplateEngine\Parser\IfTokenParser;
use Electronics\TemplateEngine\Parser\IncludeTokenParser;
use Electronics\TemplateEngine\Parser\ShowTokenParser;

class CoreExtension implements Extension
{
    protected Engine $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function getParsers(): array
    {
        return [
            new IfTokenParser(),
            new ForTokenParser(),
            new IncludeTokenParser(),
            new ExtendsTokenParser(),
            new ShowTokenParser(),
            new BlockTokenParser()
        ];
    }

    public function getMethods(): array
    {
        return [
            'raw' => [$this, 'raw'],
            'min' => [$this, 'min'],
            'max' => [$this, 'max'],
            'random' => [$this, 'random'],
            'dump' => [$this, 'dump']
        ];
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function raw(mixed $_): void
    {
        echo $_;
    }

    /**
     * @psalm-suppress MixedArgument
     * @param non-empty-array<array-key, mixed> $array
     */
    public function min(array $array): void
    {
        echo min($array);
    }

    /**
     * @psalm-suppress MixedArgument
     * @param non-empty-array<array-key, mixed> $array
     */
    public function max(array $array): void
    {
        echo max($array);
    }

    /**
     * @psalm-suppress MixedArgument
     * @param non-empty-array<array-key, mixed> $array
     */
    public function random(array $array): void
    {
        echo $array[array_rand($array)];
    }

    /**
     * @psalm-suppress MixedArgument,ForbiddenCode
     */
    public function dump(): void
    {
        foreach (func_get_args() as $_) {
            var_dump($_);
        }
    }
}