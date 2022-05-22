<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\Authenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use TBCD\BearerAuthenticatorBundle\Token\BearerToken;
use TBCD\BearerAuthenticatorBundle\TokenEncoder\TokenEncoderInterface;
use TBCD\BearerAuthenticatorBundle\TokenExtractor\TokenExtractorInterface;

class BearerAuthenticator implements AuthenticatorInterface
{

    private TokenExtractorInterface $tokenExtractor;
    private TokenEncoderInterface $tokenEncoder;
    private UserProviderInterface $userProvider;
    private AuthenticationFailureHandlerInterface $authenticationFailureHandler;

    public function __construct(TokenExtractorInterface $tokenExtractor, TokenEncoderInterface $tokenEncoder, UserProviderInterface $userProvider, AuthenticationFailureHandlerInterface $authenticationFailureHandler)
    {
        $this->tokenExtractor = $tokenExtractor;
        $this->tokenEncoder = $tokenEncoder;
        $this->userProvider = $userProvider;
        $this->authenticationFailureHandler = $authenticationFailureHandler;
    }


    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
        return $this->tokenExtractor->support($request);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): Passport
    {
        $encodedToken = $this->tokenExtractor->extract($request);

        if (!$encodedToken) {
            throw new AuthenticationException();
        }

        $userIdentifier = $this->tokenEncoder->decode($encodedToken);

        return new SelfValidatingPassport(new UserBadge($userIdentifier, [$this->userProvider, 'loadUserByIdentifier']));
    }

    /**
     * @inheritDoc
     */
    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return new BearerToken($passport->getUser(), $firewallName, $passport->getUser()->getRoles());
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->authenticationFailureHandler->onAuthenticationFailure($request, $exception);
    }
}