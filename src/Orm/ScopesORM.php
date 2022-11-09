<?php

namespace App\Orm;

use App\Domain\LeagueEntities\Scope;
use App\Model\Table\ScopesTable;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopesORM implements ScopeRepositoryInterface
{
    private ScopesTable $scopesTable;

    public function __construct(ScopesTable $scopesTable)
    {
        $this->scopesTable = $scopesTable;
    }


    public function getScopeEntityByIdentifier($identifier)
    {
        $scopesEntity = new Scope();
        $scopesEntity->setIdentifier($identifier);
        return $scopesEntity;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        return $scopes;
    }
}
