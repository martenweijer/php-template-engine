<?php

namespace Electronics\TemplateEngine\Parser;

use Electronics\TemplateEngine\Node\BlockNode;
use Electronics\TemplateEngine\Node\Node;

class BlockStack
{
    protected string $activeBlock = '';
    /** @var array<string, BlockNode> */
    protected array $nodes = [];

    public function __construct(string $startBlock = 'display')
    {
        $this->activeBlock = $startBlock;
        $this->nodes[$startBlock] = new BlockNode($startBlock);
    }

    /** @return string[] */
    public function getBlockNames(): array
    {
        return array_keys($this->nodes);
    }

    /** @return Node[] */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function startBlock(string $block): void
    {
        $name = BlockStack::convertBlockName($block);
        $this->activeBlock = $name;
        $this->nodes[$name] = new BlockNode($name);
    }

    public function stopBlock(): void
    {
        $this->activeBlock = '';
    }

    public function addNode(Node $node): void
    {
        if (empty($this->activeBlock)) {
            throw new \InvalidArgumentException('Unable to add a node because there is no active block.');
        }

        $this->nodes[$this->activeBlock]->addNode($node);
    }

    public static function convertBlockName(string $block): string
    {
        return 'block'. ucfirst($block);
    }
}