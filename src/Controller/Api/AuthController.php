<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api", name="api_")
 */
class AuthController extends AbstractController
{
    /** @var Request */
    private $request;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        Request $request,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/auth/", name="auth", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function authAction(): JsonResponse
    {
        $requestData = json_decode($this->request->getContent(), true);

        if (isset($requestData['nickname']) && isset($requestData['password'])) {
            $user = $this->userRepository->findBy('nickname', $requestData['nickname']);

            if (!is_null($user) && $this->passwordEncoder->isPasswordValid($user, $requestData['password'])) {
                $authToken = $this->userRepository->createAuthTokenForUser($user);

                return new JsonResponse(['status' => 'success', 'X-AUTH-TOKEN' => $authToken], Response::HTTP_OK);
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Wrong auth data'], Response::HTTP_BAD_REQUEST);
    }
}