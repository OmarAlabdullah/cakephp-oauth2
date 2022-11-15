<?php

namespace App\Orm;

use App\Domain\LeagueEntities\AuthCode;
use App\Model\Table\AuthorizationCodesTable;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Xel\Common\Exception\ServiceException;

class AuthorizationCodeORM implements AuthCodeRepositoryInterface
{

    private AuthorizationCodesTable $authorizationCodeTable;

    public function __construct(AuthorizationCodesTable $authorizationCodeTable)
    {
        $this->authorizationCodeTable = $authorizationCodeTable;
    }


    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $authCode = new Entity([
            'identifier' => $authCodeEntity->getIdentifier(),
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'scopes' => $authCodeEntity->getScopes(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier()
        ]);

//        $userId = $authCodeEntity->getUserIdentifier();
//        throw new ServiceException("hhh $userId");

        $this->authorizationCodeTable->save($authCode);
    }

    public function revokeAuthCode($codeId)
    {
        $authCodeEntity = $this->authorizationCodeTable->get($codeId);

        $authCodeEntity->set('revoked', true);

        $this->authorizationCodeTable->save($authCodeEntity);
    }

    public function isAuthCodeRevoked($codeId)
    {
        return $this->authorizationCodeTable->get($codeId)->get('revoked');
    }
}
