<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenExtractor;

use Symfony\Component\HttpFoundation\Request;

class ChainTokenExtractor implements TokenExtractorInterface
{

    /**
     * @var TokenExtractorInterface[]
     */
    private array $tokenExtractors;

    /**
     * @param TokenExtractorInterface[] $tokenExtractors
     */
    public function __construct(array $tokenExtractors)
    {
        $this->tokenExtractors = $tokenExtractors;
    }


    /**
     * @inheritDoc
     */
    public function support(Request $request): bool
    {
        foreach ($this->tokenExtractors as $tokenExtractor) {
            if ($tokenExtractor->support($request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function extract(Request $request): ?string
    {
        foreach ($this->tokenExtractors as $tokenExtractor) {
            if ($tokenExtractor->support($request)) {
                return $tokenExtractor->extract($request);
            }
        }

        return null;
    }
}