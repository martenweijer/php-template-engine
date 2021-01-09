<?php

namespace Electronics\TemplateEngine;

abstract class Template
{
    public function render(array $context): string
    {
        ob_start();
        $this->display($context);
        return ob_get_clean();
    }

    abstract function display(array $context): void;
}