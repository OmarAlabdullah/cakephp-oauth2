<?php

namespace App\Di\Module\IAM;

use App\Orm\AccessTokenORM;
use App\Orm\ClientORM;
use App\Orm\RefreshTokenORM;
use App\Orm\ScopesORM;
use App\Orm\UserORM;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Ray\Di\ProviderInterface;

class AuthorizationServerProvider implements ProviderInterface
{

    public function __construct(private readonly ClientORM       $clientRepository,
                                private readonly UserORM         $userRepository,
                                private readonly RefreshTokenORM $refreshTokenRepository,
                                private readonly AccessTokenORM  $accessTokenRepository,
                                private readonly ScopesORM       $scopeRepository,
                                private readonly DateInterval $accessTokenTTL
    )
    {
    }

    public function get(): AuthorizationServer
    {
        $authServer = new AuthorizationServer($this->clientRepository, $this->accessTokenRepository, $this->scopeRepository,
            dirname(__FILE__) . DS . 'id_rsa',
            dirname(__FILE__) . DS . 'id_rsa.pub'
        );
        $authServer->enableGrantType(new ClientCredentialsGrant());
        $authServer->enableGrantType(new PasswordGrant($this->userRepository, $this->refreshTokenRepository));
        $authServer->enableGrantType(new RefreshTokenGrant($this->refreshTokenRepository));
        $authServer->enableGrantType(new ImplicitGrant($this->accessTokenTTL));
        return $authServer;
    }
}
