<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenExtractor;

use Symfony\Component\HttpFoundation\Request;

class HeaderTokenExtractor implements TokenExtractorInterface
{

    public const HEADER_NAME = 'Authorization';
    public const PREFIX = 'Bearer ';

    /**
     * @inheritDoc
     */
    public function support(Request $request): bool
    {
        return $request->headers->has(self::HEADER_NAME)
            && str_starts_with($request->headers->get(self::HEADER_NAME), self::PREFIX);
    }

    /**
     * @inheritDoc
     */
    public function extract(Request $request): ?string
    {
        $authorizationHeader = $request->headers->get(self::HEADER_NAME);
        return str_replace(self::PREFIX, '', $authorizationHeader);
    }
}