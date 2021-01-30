<?php

namespace Electronics\TemplateEngine\Loader;

class FilesystemLoader implements Loader
{
    /** @var array<string, string> */
    protected array $paths = [];
    protected ?string $cachePath;

    public function __construct(string $path, string $cachePath = null)
    {
        $this->paths[''] = $path;
        $this->cachePath = $cachePath;
    }

    public function addNamespace(string $namespace, string $path): void
    {
        $this->paths[$namespace] = $path;
    }

    public function getContents(string $template): string
    {
        list($namespace, $template) = $this->parseTemplate($template);

        $file = $this->getFilePath($namespace, $template);
        if (!file_exists($file)) {
            throw new \RuntimeException(sprintf('Template "%s" not found, looked in "%s"', $template, $file));
        }

        return file_get_contents($file);
    }

    public function isCacheEnabled(): bool
    {
        return $this->cachePath != null;
    }

    public function isFresh(string $template): bool
    {
        $cachedFile = $this->getCacheFilePath($template);
        if (!file_exists($cachedFile)) {
            return false;
        }

        list($namespace, $template) = $this->parseTemplate($template);
        $file = $this->getFilePath($namespace, $template);
        return filemtime($cachedFile) >= filemtime($file);
    }

    public function addToCache(string $template, string $compiled): void
    {
        $cachedFile = $this->getCacheFilePath($template);
        file_put_contents($cachedFile, $compiled);
    }

    public function loadFromCache(string $template): void
    {
        $file = $this->getCacheFilePath($template);
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('Could not load the cached template "%s", (looked in: "%s").', $template, $file));
        }

        require $file;
    }

    protected function getFilePath(string $namespace, string $template): string
    {
        if (!isset($this->paths[$namespace])) {
            throw new \InvalidArgumentException(sprintf('No template directory found for namespace "%s".', $namespace));
        }

        return $this->paths[$namespace] . DIRECTORY_SEPARATOR . $template;
    }

    protected function getCacheFilePath(string $template): string
    {
        if ($this->cachePath == null) {
            throw new \RuntimeException('Cache is not enabled.');
        }

        return $this->cachePath . DIRECTORY_SEPARATOR . $template .'.php';
    }

    /**
     * @param string $template
     * @return string[]
     */
    protected function parseTemplate(string $template): array
    {
        if (false !== $pos = strpos($template, '::')) {
            return [substr($template, 0, $pos), substr($template, $pos + 2)];
        }

        return ['', $template];
    }
}