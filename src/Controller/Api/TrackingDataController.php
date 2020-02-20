<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\TrackingDataCreateType;
use App\Repository\TrackingDataRepository;
use App\ValueObject\TrackingData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function createAction(Request $request, UserInterface $user): JsonResponse
    {
        $trackingData = new TrackingData();
        $trackingData->setUserId($this->getUserIdForTrackingData($request, $user));

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
     * @Route("/", name="get_all", methods={"GET"})
     *
     * This method is used only for testing purpose
     *
     * @param TrackingDataRepository $trackingDataRepository
     * @return JsonResponse
     */
    public function getAllAction(TrackingDataRepository $trackingDataRepository): JsonResponse
    {
        $trackingData = $trackingDataRepository->fetchAll();

        return new JsonResponse(['status' => 'success', 'data' => $trackingData], Response::HTTP_OK);
    }

    /**
     * Get userId for trackingData from current userId if it's an authorized user
     * or get USER-AGENT-TOKEN header if its unknown user
     *
     * @param Request $request
     * @param UserInterface $user
     * @return string|null
     */
    private function getUserIdForTrackingData(Request $request, UserInterface $user): ?string
    {
        $userId = null;

        if (!is_null($user->getId())) {
            $userId = (string) $user->getId();
        } elseif ($request->headers->has('USER-AGENT-TOKEN')) {
            $userId = $request->headers->get('USER-AGENT-TOKEN');
        }

        return $userId;
    }
}