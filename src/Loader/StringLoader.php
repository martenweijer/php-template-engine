<?php

namespace Electronics\TemplateEngine\Loader;

class StringLoader implements Loader
{
    public function getContents(string $template): string
    {
        return $template;
    }
}