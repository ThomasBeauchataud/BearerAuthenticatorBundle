<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenExtractor;

use Symfony\Component\HttpFoundation\Request;

class RequestTokenExtractor implements TokenExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function support(Request $request): bool
    {
        return !$request->isMethod(Request::METHOD_GET)
            && $request->request->has('access_token')
            && $request->headers->get('Content-Type') === 'application/x-www-form-urlencoded';
    }

    /**
     * @inheritDoc
     */
    public function extract(Request $request): ?string
    {
        return $request->request->get('access_token');
    }
}