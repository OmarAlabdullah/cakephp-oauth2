<?php

namespace App\Orm;

use App\Domain\LeagueEntities\AccessToken;
use App\Model\Table\AccessTokensTable;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenORM implements AccessTokenRepositoryInterface
{
    private AccessTokensTable $accessTokensTable;

    /**
     * @param AccessTokensTable $accessTokensTable
     */
    public function __construct(AccessTokensTable $accessTokensTable)
    {
        $this->accessTokensTable = $accessTokensTable;
    }


    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        /** @var int|string|null $userIdentifier */
        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;

    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $accessTokensEntity = new Entity([
            'user_id' => $accessTokenEntity->getUserIdentifier(),
            'revoked' => false,
            'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'identifier' => $accessTokenEntity->getIdentifier(),
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
            'scopes' => ''
        ]);

        $this->accessTokensTable->save($accessTokensEntity);
    }

    public function revokeAccessToken($tokenId)
    {
        $accessTokenEntity = $this->accessTokensTable->get($tokenId);

        $accessTokenEntity->set('revoked', true);

        $this->accessTokensTable->save($accessTokenEntity);
    }

    public function isAccessTokenRevoked($tokenId)
    {
        return $this->accessTokensTable->get($tokenId)->get('revoked');
    }
}
