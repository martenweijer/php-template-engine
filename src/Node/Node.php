<?php

namespace Electronics\TemplateEngine\Node;

interface Node
{
    function write(Writer $writer): void;
}