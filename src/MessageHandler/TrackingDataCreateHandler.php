<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\TrackingDataMessage;
use SocialTech\StorageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TrackingDataCreateHandler implements MessageHandlerInterface
{
    /** @var StorageInterface */
    private $jsonStorage;

    /** @var string */
    private $storagePath;

    /**
     * @param StorageInterface $jsonStorage
     * @param string $storagePath
     */
    public function __construct(StorageInterface $jsonStorage, string $storagePath)
    {
        $this->jsonStorage = $jsonStorage;
        $this->storagePath = $storagePath;
    }

    /**
     * @param TrackingDataMessage $trackingData
     */
    public function __invoke(TrackingDataMessage $trackingData)
    {
        $trackingData = [
            "id_user" => $trackingData->getUserId(),
            "source_label" => $trackingData->getSourceLabel(),
            "date_created" => $trackingData->getCreatedDate()->format('Y-m-d H:i:s')
        ];

        //TODO генерировать уникальный ID через автоинкремент

        if ($this->jsonStorage->exists($this->storagePath)) {
            $this->jsonStorage->append($this->storagePath, json_encode($trackingData));
        } else {
            $this->jsonStorage->store($this->storagePath, json_encode($trackingData));
        }
    }
}