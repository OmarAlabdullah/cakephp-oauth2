<?php

namespace App\Orm;

use App\Domain\LeagueEntities\RefreshToken;
use App\Domain\RefreshAccessTokenClass;
use App\Model\Table\AccessTokensTable;
use App\Model\Table\RefreshTokensTable;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Xel\Common\EntityConverter;

class RefreshTokenORM implements RefreshTokenRepositoryInterface
{

    private RefreshTokensTable $refreshTokensTable;
    private AccessTokensTable $accessTokensTable;

    public function __construct(RefreshTokensTable $refreshTokensTable, AccessTokensTable $accessTokensTable){
        $this->refreshTokensTable= $refreshTokensTable;
        $this->accessTokensTable = $accessTokensTable;

    }



    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $refreshTokensEntity = new Entity([
            // TODO"Cannot convert value of type `App\\Model\\Entity\\AccessToken` to bool"
           // 'revoked' => $refreshTokenEntity->getAccessToken(),
            'identifier' => $refreshTokenEntity->getIdentifier(),
            'revoked' => 0,
            'expires_at' => $refreshTokenEntity->getExpiryDateTime(),
            'access_token_id' => $refreshTokenEntity->getIdentifier()
        ]);

        $this->refreshTokensTable->save($refreshTokensEntity);
    }


    public function revokeRefreshToken($tokenId)
    {
        $refreshTokenEntity = $this->refreshTokensTable->get($tokenId);

        $refreshTokenEntity->set('revoked', true);

        $this->refreshTokensTable->save($refreshTokenEntity);
    }

    /**
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId): bool {

        return $this->refreshTokensTable->get($tokenId)->get('revoked');

    }

}
