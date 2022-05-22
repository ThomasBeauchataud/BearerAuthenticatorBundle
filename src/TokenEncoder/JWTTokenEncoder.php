<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenEncoder;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Symfony\Component\Security\Core\User\UserInterface;
use TBCD\BearerAuthenticatorBundle\Exception\TokenEncoderException;

class JWTTokenEncoder implements TokenEncoderInterface
{

    private Configuration $config;

    public function __construct(string $secret)
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded($secret));
        $this->config->setValidationConstraints(new SignedWith(new Sha256(), InMemory::base64Encoded($secret)));
    }


    /**
     * @inheritDoc
     */
    public function encode(UserInterface $user): string
    {
        $token = $this->config->builder()
            ->withClaim('userIdentifier', $user->getUserIdentifier())
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }

    /**
     * @inheritDoc
     */
    public function decode(string $encodedToken): string
    {
        try {

            $token = $this->config->parser()->parse($encodedToken);

            assert($token instanceof UnencryptedToken);

            $constraints = $this->config->validationConstraints();
            $this->config->validator()->assert($token, ...$constraints);

        } catch (\Exception $e) {
            throw new TokenEncoderException($e->getMessage(), 0, $e);
        }

        return $token->claims()->get('userIdentifier');
    }

}