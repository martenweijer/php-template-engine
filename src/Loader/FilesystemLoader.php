<?php

namespace Electronics\TemplateEngine\Loader;

class FilesystemLoader implements Loader
{
    /** @var array<string, string> */
    protected array $paths = [];

    public function __construct(string $path)
    {
        $this->paths[''] = $path;
    }

    public function addNamespace(string $namespace, string $path): void
    {
        $this->paths[$namespace] = $path;
    }

    public function getContents(string $template): string
    {
        if (false !== $pos = strpos($template, '::')) {
            $namespace = substr($template, 0, $pos);
            $template = substr($template, $pos + 2);
        }

        /** @psalm-suppress MixedArgument */
        $directory = $this->getDirectory($namespace ?? '');

        $filePath = $directory . DIRECTORY_SEPARATOR . $template;
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf('Template "%s" not found, looked in "%s"', $template, $filePath));
        }

        return file_get_contents($filePath);
    }

    protected function getDirectory(string $namespace): string
    {
        if (!isset($this->paths[$namespace])) {
            throw new \InvalidArgumentException(sprintf('No template directory found for namespace "%s".', $namespace));
        }

        return $this->paths[$namespace];
    }
}