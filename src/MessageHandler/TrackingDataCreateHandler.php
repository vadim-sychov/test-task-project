<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\ValueObject\TrackingData;
use App\Repository\TrackingDataRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TrackingDataCreateHandler implements MessageHandlerInterface
{
    /** @var TrackingDataRepository */
    private $trackingDataRepository;

    /**
     * @param TrackingDataRepository $trackingDataRepository
     */
    public function __construct(TrackingDataRepository $trackingDataRepository)
    {
        $this->trackingDataRepository = $trackingDataRepository;
    }

    /**
     * @param TrackingData $trackingData
     */
    public function __invoke(TrackingData $trackingData)
    {
        $this->trackingDataRepository->save($trackingData);
    }
}