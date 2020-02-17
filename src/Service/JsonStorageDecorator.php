<?php
declare(strict_types=1);

namespace App\Service;

use SocialTech\StorageInterface;

/**
 * This class implements the Decorator pattern
 * and it's responsible that the data that will be passed next will be correctly stored to json file
 */
class JsonStorageDecorator implements StorageInterface
{
    /** @var StorageInterface */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function store(string $path, string $content): void
    {
        // Remove closing curly brace
        $content = substr($content, 0, -1);

        $this->storage->store($path, $content);
    }

    /**
     * @inheritDoc
     */
    public function append(string $path, string $content): void
    {
        // Remove open and closing curly brace
        $content = substr($content, 1, -1);

        // Replace open curly brace on comma and whitespace
        $content = ', ' . $content;

        $this->storage->append($path, $content);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return $this->storage->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function load(string $path): string
    {
        $content = $this->storage->load($path);

        // Add closing curly brace
        return $content . '}';
    }
}