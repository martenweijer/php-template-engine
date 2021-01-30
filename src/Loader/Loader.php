<?php

namespace Electronics\TemplateEngine\Loader;

interface Loader
{
    function getContents(string $template): string;
    function isCacheEnabled(): bool;
    function isFresh(string $template): bool;
    function addToCache(string $template, string $compiled): void;
}