<?php

/*
 * Author Thomas Beauchataud
 * Since 22/05/2022
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TBCD\BearerAuthenticatorBundle\AuthenticationHandler\BearerAuthenticationFailureHandler;
use TBCD\BearerAuthenticatorBundle\Authenticator\BearerAuthenticator;
use TBCD\BearerAuthenticatorBundle\TokenEncoder\JWTTokenEncoder;
use TBCD\BearerAuthenticatorBundle\TokenEncoder\TokenEncoderInterface;
use TBCD\BearerAuthenticatorBundle\TokenExtractor\HeaderTokenExtractor;
use TBCD\BearerAuthenticatorBundle\TokenExtractor\ChainTokenExtractor;
use TBCD\BearerAuthenticatorBundle\TokenExtractor\QueryTokenExtractor;
use TBCD\BearerAuthenticatorBundle\TokenExtractor\RequestTokenExtractor;
use TBCD\BearerAuthenticatorBundle\TokenExtractor\TokenExtractorInterface;
use function symfony\component\dependencyinjection\loader\configurator\env;
use function symfony\component\dependencyinjection\loader\configurator\service;

return function (ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('TBCD\\BearerAuthenticatorBundle\\', '../../../src/')
        ->exclude('../../../src/{DependencyInjection,Resources}');

    $services->get(JWTTokenEncoder::class)
        ->bind('$secret', env('APP_SECRET')->string());

    $services->get(ChainTokenExtractor::class)
        ->bind('$tokenExtractors', [service(QueryTokenExtractor::class), service(HeaderTokenExtractor::class), service(RequestTokenExtractor::class)]);

    $services->alias(TokenExtractorInterface::class, ChainTokenExtractor::class);

    $services->get(BearerAuthenticator::class)
        ->bind('$tokenExtractor', service(ChainTokenExtractor::class))
        ->bind('$authenticationFailureHandler', service(BearerAuthenticationFailureHandler::class))
        ->bind('$tokenEncoder', service(JWTTokenEncoder::class));

    $services->alias(TokenEncoderInterface::class, JWTTokenEncoder::class);
};