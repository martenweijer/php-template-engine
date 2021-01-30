<?php

namespace Electronics\TemplateEngine\Loader;

class StringLoader implements Loader
{
    public function getContents(string $template): string
    {
        return $template;
    }

    public function isFresh(string $template): bool
    {
        return false;
    }

    public function isCacheEnabled(): bool
    {
        return false;
    }

    public function addToCache(string $template, string $compiled): void
    {
    }
}