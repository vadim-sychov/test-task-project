<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\UserCreateType;
use App\ValueObject\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $user = new User();

        $form = $this->createForm(UserCreateType::class, $user);
        $form->submit(json_decode($request->getContent(),true));

        if ($form->isValid()) {
            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            $this->dispatchMessage($user, [new AmqpStamp($this->routingKey)]);

            return new JsonResponse(['status' => 'success'], Response::HTTP_CREATED);
        }

        $errorMessages = [];
        foreach ($form->getErrors(true) as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return new JsonResponse(['status' => 'error', 'message' => $errorMessages], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/", name="get_all", methods={"GET"})
     *
     * This method is used only for testing purpose
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getAllAction(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->fetchAll();

        return new JsonResponse(['status' => 'success', 'data' => $users], Response::HTTP_OK);
    }
}