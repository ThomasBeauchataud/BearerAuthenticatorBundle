<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenExtractor;

use Symfony\Component\HttpFoundation\Request;

interface TokenExtractorInterface
{

    /**
     * @param Request $request
     * @return bool
     */
    public function support(Request $request): bool;

    /**
     * @param Request $request
     * @return string|null
     */
    public function extract(Request $request): ?string;

}