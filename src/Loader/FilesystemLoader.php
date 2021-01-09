<?php

namespace Electronics\TemplateEngine\Loader;

class FilesystemLoader implements Loader
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getContents(string $template): string
    {
        $filePath = $this->path . DIRECTORY_SEPARATOR . $template;
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf('Template "%s" not found, looked in "%s"', $template, $filePath));
        }

        return file_get_contents($filePath);
    }
}