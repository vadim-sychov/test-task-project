<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\UserCreateType;
use App\Message\UserCreateMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SocialTech\StorageInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="create", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $userMessage = new UserCreateMessage();

        $form = $this->createForm(UserCreateType::class, $userMessage);
        $form->submit(json_decode($request->getContent(),true));

        if ($form->isValid()) {
            $this->dispatchMessage($userMessage, [new AmqpStamp('user_create')]);

            return new JsonResponse(['status' => 'success']);
        }

        $errorMessages = [];
        foreach ($form->getErrors(true) as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return new JsonResponse(['status' => 'error', 'data' => $errorMessages], 400);
    }

    /**
     * This is temporary method and used only for testing purpose
     * @Route("/", name="read", methods={"GET"})
     *
     * @param StorageInterface $storage
     * @return JsonResponse
     */
    public function readAction(StorageInterface $storage): JsonResponse
    {
        $responseData = $storage->load('../storage/users.json');

        return new JsonResponse(['status' => 'success', 'data' => json_decode($responseData, true)]);
    }
}