<?php
declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     *
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     *
     * @inheritDoc
     */
    public function getCredentials(Request $request): ?string
    {
        return $request->headers->get('X-AUTH-TOKEN');
    }

    /**
     * @inheritDoc
     */
    public function getUser($authToken, UserProviderInterface $userProvider)
    {
        if (null === $authToken) {
            // The token header was empty, authentication fails with 401
            return;
        }

        // if a User is returned, checkCredentials() is called
        return $this->userRepository->findByAuthToken($authToken);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case
        // return true to cause authentication success
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['status' => 'error', 'message' => 'Authentication failure, X-AUTH-TOKEN is not valid'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Called when authentication is needed, but it's not sent
     *
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['status' => 'error', 'message' => 'Need authentication, X-AUTH-TOKEN header required'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
