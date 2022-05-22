<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\AuthenticationHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use TBCD\BearerAuthenticatorBundle\Exception\TokenEncoderException;

class BearerAuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($exception instanceof TokenEncoderException) {
            return new Response("invalid_token", 401);
        }

        return new Response("invalid_request", 400);
    }
}