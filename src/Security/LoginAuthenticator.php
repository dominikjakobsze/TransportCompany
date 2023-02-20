<?php

namespace App\Security;

use App\Entity\User;
use App\Service\JsonWebTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private JsonWebTokenService $jsonWebTokenService
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        //then change GET to POST
        return $request->getPathInfo() === '/api/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        return new Passport(
            new UserBadge($email),
            new CustomCredentials(function ($credentials, User $user) {
                return password_verify($credentials, $user->getPassword());
            }, $password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $jwt = $this->jsonWebTokenService->createToken([
            'email' => $token->getUser()->getEmail(),
            'roles' => $token->getUser()->getRoles(),
            'id' => $token->getUser()->getId(),
            'expiration' => (new \DateTime('now', new \DateTimeZone('Europe/Warsaw')))->modify('+2 hours')
        ]);
        return new JsonResponse(data: ['jwtToken' => $jwt], status: 200, headers: [], json: false);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new UserNotFoundException(message: "bad password or email", code: 401);
    }
}