<?php

namespace App\Orm;

use App\Model\Entity\AuthCode;
use App\Model\Table\AuthorizationCodeTable;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthorizationCodeORM implements AuthCodeRepositoryInterface
{

    private  AuthorizationCodeTable $authorizationCodeTable;

    public function __construct(AuthorizationCodeTable $authorizationCodeTable)
    {
        $this->authorizationCodeTable = $authorizationCodeTable;
    }


    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $authorizationCode = $this->authorizationCodeTable->find($authCodeEntity->getIdentifier());

        if (null !== $authorizationCode) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->authorizationCodeTable->save(new Entity([
            'identifier' => $authCodeEntity->getIdentifier(),
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'scopes' => $authCodeEntity->getScopes(),
            'client_id' => $authCodeEntity->getClient()
        ]));
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
