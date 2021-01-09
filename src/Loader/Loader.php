<?php

namespace Electronics\TemplateEngine\Loader;

interface Loader
{
    function getContents(string $template): string;
}