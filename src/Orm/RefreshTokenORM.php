<?php

namespace App\Orm;

use App\Domain\LeagueEntities\RefreshToken;
use App\Model\Table\RefreshTokensTable;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenORM implements RefreshTokenRepositoryInterface
{

    private RefreshTokensTable $refreshTokensTable;

    /**
     * @param RefreshTokensTable $refreshTokensTable
     */
    public function __construct(RefreshTokensTable $refreshTokensTable)
    {
        $this->refreshTokensTable = $refreshTokensTable;
    }


    public function getNewRefreshToken(): RefreshToken
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $refreshTokensEntity = new Entity([
            'identifier' => $refreshTokenEntity->getIdentifier(),
            'revoked' => false,
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
    public function isRefreshTokenRevoked($tokenId): bool
    {

        return $this->refreshTokensTable->get($tokenId)->get('revoked');

    }

}
