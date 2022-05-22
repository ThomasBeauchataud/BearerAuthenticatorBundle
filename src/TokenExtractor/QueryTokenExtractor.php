<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenExtractor;

use Symfony\Component\HttpFoundation\Request;

class QueryTokenExtractor implements TokenExtractorInterface
{

    public const PARAMETER_NAME = 'access_token';

    /**
     * @inheritDoc
     */
    public function support(Request $request): bool
    {
        return $request->query->has(self::PARAMETER_NAME);
    }

    /**
     * @inheritDoc
     */
    public function extract(Request $request): ?string
    {
        return $request->query->get(self::PARAMETER_NAME);
    }
}