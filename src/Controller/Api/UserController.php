<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\UserCreateType;
use App\Message\UserMessage;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractController
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
        $userMessage = new UserMessage();

        $form = $this->createForm(UserCreateType::class, $userMessage);
        $form->submit(json_decode($request->getContent(),true));

        if ($form->isValid()) {
            $this->dispatchMessage($userMessage, [new AmqpStamp($this->routingKey)]);

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
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function readAction(UserRepository $userRepository): JsonResponse
    {
        //TODO check admin token
        $responseData = $userRepository->fetchAll();

        return new JsonResponse(['status' => 'success', 'data' => $responseData]);
    }
}