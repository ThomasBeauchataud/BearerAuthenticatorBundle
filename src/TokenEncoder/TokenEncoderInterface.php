<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

namespace TBCD\BearerAuthenticatorBundle\TokenEncoder;

use Symfony\Component\Security\Core\User\UserInterface;

interface TokenEncoderInterface
{

    /**
     * @param UserInterface $user
     * @return string
     */
    public function encode(UserInterface $user): string;

    /**
     * @param string $encodedToken
     * @return string
     */
    public function decode(string $encodedToken): string;

}