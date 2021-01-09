<?php

namespace Electronics\TemplateEngine;

abstract class Template
{
    protected Engine $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render(array $context): string
    {
        ob_start();
        $this->display($context);
        return ob_get_clean();
    }

    abstract function display(array $context): void;

    protected function getVariableAsString(string $variable, array $context): string
    {
        $string = (string) $this->getVariable($variable, $context);
        return $this->engine->escapeString($string);
    }

    protected function getVariable(string $variable, mixed $context): mixed
    {
        if (is_array($context) && array_key_exists($variable, $context)) {
            return $context[$variable];
        }

        return null;
    }
}