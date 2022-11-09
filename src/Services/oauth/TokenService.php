<?php

namespace App\Services\oauth;



use App\Domain\LeagueEntities\AccessToken;
use App\Domain\TokenRequest;
use App\Orm\AccessTokenORM;
use App\Orm\ClientORM;
use Cake\I18n\FrozenTime;

class TokenService implements TokenServiceInterface {
    private AccessTokenORM $accessTokenORM;

    private ClientORM $clientORM;

    /**
     * @param AccessTokenORM $accessTokenORM
     * @param ClientORM $clientORM
     */
    public function __construct(AccessTokenORM $accessTokenORM, ClientORM $clientORM)
    {
        $this->accessTokenORM = $accessTokenORM;
        $this->clientORM = $clientORM;
    }


    public function accessToken(TokenRequest $tokenRequest): string
    {
        $accessToken = new AccessToken();

        $accessToken->setIdentifier(3342);
        $accessToken->setUserIdentifier(1);
        $accessToken->setClient($this->clientORM->getClientEntity("omar"));
        $accessToken->setExpiryDateTime(FrozenTime::createFromFormat(
            'Y-m-d H:i:s',
            '2021-01-31 22:11:30',
            'America/New_York'
        ));

        $this->accessTokenORM->persistNewAccessToken($accessToken);
        return "";
    }
}
