<?php

namespace Electronics\TemplateEngine\Extension;

use Electronics\TemplateEngine\Parser\TokenParser;

interface Extension
{
    /**
     * @return TokenParser[]
     */
    function getParsers(): array;
}