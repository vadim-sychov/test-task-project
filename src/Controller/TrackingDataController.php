<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\TrackingDataCreateType;
use App\Message\TrackingDataMessage;
use SocialTech\StorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tracking-data", name="api_tracking_data_")
 */
class TrackingDataController extends AbstractController
{
    /**
     * @Route("/", name="create", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $trackingDataMessage = new TrackingDataMessage();

        $form = $this->createForm(TrackingDataCreateType::class, $trackingDataMessage);
        $form->submit(json_decode($request->getContent(),true));

        if ($form->isValid()) {
            $this->dispatchMessage($trackingDataMessage, [new AmqpStamp('tracking_data_create')]);

            return new JsonResponse(['status' => 'success']);
        }

        $errorMessages = [];
        foreach ($form->getErrors(true) as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return new JsonResponse(['status' => 'error', 'data' => $errorMessages], 400);
    }

    /**
     * This method is used only for testing purpose
     *
     * @Route("/", name="read", methods={"GET"})
     *
     * @param StorageInterface $storage
     * @return JsonResponse
     */
    public function readAction(StorageInterface $storage): JsonResponse
    {
        //TODO check admin token
        //TODO use TrackingDataRepository
        $responseData = $storage->load('../storage/tracking-data.json');

        return new JsonResponse(['status' => 'success', 'data' => json_decode($responseData, true)]);
    }
}