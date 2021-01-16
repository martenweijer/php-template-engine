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

    public function callMethod(string $method, array $args): void
    {
        $callable = $this->engine->getMethod($method);
        call_user_func_array($callable, $args);
    }

    public function getVariableAsString(string $variable, array $context): string
    {
        $string = (string) $this->getVariable($variable, $context);
        return $this->engine->escapeString($string);
    }

    public function getVariable(string $variable, mixed $context): mixed
    {
        if (is_array($context) && array_key_exists($variable, $context)) {
            return $context[$variable];
        }

        if (is_object($context) && isset($context->$variable)) {
            return $context->$variable;
        }

        if (false !== $pos = strpos($variable, '.')) {
            $var = substr($variable, 0, $pos);
            $name = substr($variable, $pos + 1);
            $_ = $this->getVariable($var, $context);

            if ($_ != null) {
                return $this->getVariable($name, $_);
            }
        }

        return null;
    }
}