<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\TrackingDataMessage;
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
     * @param TrackingDataMessage $trackingDataMessage
     */
    public function __invoke(TrackingDataMessage $trackingDataMessage)
    {
        $this->trackingDataRepository->createFromMessage($trackingDataMessage);
    }
}