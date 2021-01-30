<?php

namespace Electronics\TemplateEngine\Parser;

class ParserCollection
{
    protected array $parsers = [];
    protected MethodParser $methodParser;

    public function __construct()
    {
        $this->methodParser = new MethodParser();
    }

    public function addParser(TokenParser $parser): void
    {
        /** @var string $identifier */
        foreach ($parser->getIdentifiers() as $identifier) {
            $this->parsers[$identifier] = $parser;
        }
    }

    public function hasParser(string $identifier): bool
    {
        return isset($this->parsers[$identifier]);
    }

    public function getParser(string $identifier): TokenParser
    {
        if (!$this->hasParser($identifier)) {
            throw new \InvalidArgumentException(sprintf('Parser with identifier "%s" not found.', $identifier));
        }

        /** @var TokenParser $parser */
        $parser = $this->parsers[$identifier];
        return $parser;
    }

    public function getDedicatedMethodParser(): MethodParser
    {
        return $this->methodParser;
    }

    public function setDedicatedMethodParser(MethodParser $methodParser): void
    {
        $this->methodParser = $methodParser;
    }
}