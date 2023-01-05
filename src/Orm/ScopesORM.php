<?php

namespace App\Orm;

use App\Model\Entity\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopesORM implements ScopeRepositoryInterface {


    public function getScopeEntityByIdentifier($identifier): Scope {
        $scopesEntity = new Scope();
        $scopesEntity->setIdentifier($identifier);
        return $scopesEntity;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array {
        return $scopes;
    }
}
