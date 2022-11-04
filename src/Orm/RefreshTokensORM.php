<?php

namespace App\Orm;

use App\Domain\TokenRequest;
use App\Model\DbRefreshToken;
use App\Model\Entity\RefreshToken;
use App\Model\Table\AccessTokensTable;
use App\Model\Table\RefreshTokensTable;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Xel\Common\EntityConverter;
use Xel\Common\ErrorCodes;
use Xel\Common\Exception\ServiceException;

class RefreshTokensORM implements RefreshTokenRepositoryInterface
{

    private RefreshTokensTable $refreshTokensTable;
    private AccessTokensTable $accessTokensTable;

    public function __construct(RefreshTokensTable $refreshTokensTable, AccessTokensTable $accessTokensTable){
        $this->refreshTokensTable= $refreshTokensTable;
        $this->accessTokensTable = $accessTokensTable;

    }

    /**
     * @throws ServiceException
     */
    public function saveRefreshToken(TokenRequest $tokenRequest): void {

        $expires_at = FrozenTime::now()  ;
        $accessTokenId = $tokenRequest->getPassword();
        $revoked = 1;



        $refreshTokensEntity = new Entity([
            'revoked' => $revoked,
            'accessTokenId' => $accessTokenId,
            'expires_at' => $expires_at
        ]);


        try {
            $this->refreshTokensTable->saveOrFail($refreshTokensEntity);
        } catch (\Exception $e) {
            throw new ServiceException("Cannot save refresh Tokens, Error: $e", ErrorCodes::SERVER_ERROR, $e);
        }

    }


    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $refreshToken = $this->refreshTokensTable->find($refreshTokenEntity->getIdentifier());

        if (null !== $refreshToken) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $refreshTokensEntity = new Entity([
            'revoked' => $refreshTokenEntity->getAccessToken(),
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
