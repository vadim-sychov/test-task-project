<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\TrackingDataCreateType;
use App\ValueObject\TrackingData;
use App\Repository\TrackingDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tracking-data", name="api_tracking_data_")
 */
class TrackingDataController extends AbstractController
{
    /** @var string */
    private $routingKey;

    /**
     * @param string $routingKey
     */
    public function __construct(string $routingKey)
    {
        $this->routingKey = $routingKey;
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $trackingData = new TrackingData();

        $form = $this->createForm(TrackingDataCreateType::class, $trackingData);
        $form->submit(json_decode($request->getContent(),true));

        if ($form->isValid()) {
            $this->dispatchMessage($trackingData, [new AmqpStamp($this->routingKey)]);

            return new JsonResponse(['status' => 'success'], Response::HTTP_CREATED);
        }

        $errorMessages = [];
        foreach ($form->getErrors(true) as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return new JsonResponse(['status' => 'error', 'message' => $errorMessages], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * This method is used only for testing purpose,
     * it's show all tracking data only for authorized user
     *
     * @Route("/", name="read", methods={"GET"})
     *
     * @param TrackingDataRepository $trackingDataRepository
     * @return JsonResponse
     */
    public function readAction(TrackingDataRepository $trackingDataRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $responseData = $trackingDataRepository->fetchAll();

        return new JsonResponse(['status' => 'success', 'data' => $responseData], Response::HTTP_OK);
    }
}