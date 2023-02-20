<?php

namespace App\Security;

use App\Entity\User;
use App\Service\JsonWebTokenService;
use http\Exception\UnexpectedValueException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class VerifyJwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private JsonWebTokenService $jsonWebTokenService,
        private CacheInterface      $cache
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-token');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('X-token');
        $time = $this->jsonWebTokenService->getCurrentTime();
        try {
            $userRetrieved = $this->jsonWebTokenService->decodeToken($token);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException(message: 'Token is wrong', code: 401);
        }
        if ($time->format('Y-m-d H:i:s:u') > $userRetrieved['expiration']) {
            throw new CustomUserMessageAuthenticationException(message: 'Token is expired', code: 401);
        }
        $passportRetrieved = $this->cache->getItem($token);
        if ($passportRetrieved->isHit()) {
            return new Passport(
                new UserBadge($userRetrieved['email'], function () use ($passportRetrieved) {
                    return $passportRetrieved->get();
                }),
                new CustomCredentials(function () {
                    return true;
                }, $userRetrieved['email'])
            );
        }
        $passportRetrieved->expiresAfter(50);
        return new Passport(
            new UserBadge($userRetrieved['email']),
            new CustomCredentials(function ($credentials, User $user) use ($passportRetrieved) {
                $passportRetrieved->set($user);
                $this->cache->save($passportRetrieved);
                return true;
            }, $userRetrieved['email'])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        dd('success');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
