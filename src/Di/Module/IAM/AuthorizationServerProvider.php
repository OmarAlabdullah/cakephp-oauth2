<?php

namespace App\Di\Module\IAM;

use App\Orm\AccessTokenORM;
use App\Orm\AuthorizationCodeORM;
use App\Orm\ClientORM;
use App\Orm\RefreshTokenORM;
use App\Orm\ScopesORM;
use App\Orm\UserORM;
use Cake\I18n\FrozenTime;
use Cassandra\Date;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Ray\Di\ProviderInterface;
use Xel\Cake\Util\DateTimeUtil;

class AuthorizationServerProvider implements ProviderInterface
{

    public function __construct(private readonly ClientORM       $clientRepository,
                                private readonly UserORM         $userRepository,
                                private readonly RefreshTokenORM $refreshTokenRepository,
                                private readonly AccessTokenORM  $accessTokenRepository,
                                private readonly ScopesORM       $scopeRepository,
                                private readonly AuthorizationCodeORM $authorizationCodeORM
                                )
    {
    }

    public function get(): AuthorizationServer
    {
        $authServer = new AuthorizationServer($this->clientRepository, $this->accessTokenRepository, $this->scopeRepository,
            dirname(__FILE__) . DS . 'id_rsa',
            dirname(__FILE__) . DS . 'id_rsa.pub'
        );

        $authCodeTTL = new \DateInterval("P1D");
        $accessTokenTTL = new \DateInterval("P1D");
        $authServer->enableGrantType(new \League\OAuth2\Server\Grant\ClientCredentialsGrant());
        $authServer->enableGrantType(new PasswordGrant($this->userRepository, $this->refreshTokenRepository));
        $authServer->enableGrantType(new RefreshTokenGrant($this->refreshTokenRepository));
        $authServer->enableGrantType(new AuthCodeGrant($this->authorizationCodeORM,$this->refreshTokenRepository, $authCodeTTL));
        $authServer->enableGrantType(new ImplicitGrant($accessTokenTTL));

        return $authServer;
    }
}
