<?php
declare(strict_types=1);

namespace App\ValueObject;

use DateTimeImmutable;

class TrackingData
{
    /** @var string */
    private $userId;

    /** @var string */
    private $sourceLabel;

    /** @var DateTimeImmutable */
    private $createdDate;

    public function __construct()
    {
        $this->createdDate = new DateTimeImmutable();
    }

    /**
     * @return null|string
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getSourceLabel(): ?string
    {
        return $this->sourceLabel;
    }

    /**
     * @param string $sourceLabel
     */
    public function setSourceLabel(string $sourceLabel): void
    {
        $this->sourceLabel = $sourceLabel;
    }

    /**
     * @return null|DateTimeImmutable
     */
    public function getCreatedDate(): ?DateTimeImmutable
    {
        return $this->createdDate;
    }
}