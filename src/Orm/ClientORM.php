<?php

namespace App\Orm;

use App\Domain\LeagueEntities\Client;
use App\Model\Table\ClientsTable;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientORM implements ClientRepositoryInterface
{

    private ClientsTable $clientsTable;

    public function __construct(ClientsTable $clientsTable)
    {
        $this->clientsTable = $clientsTable;
    }

    public function getClientEntity($clientIdentifier): ClientEntityInterface
    {
        {
            $clientEntity = new Client();
            $clientEntity->setIdentifier($clientIdentifier);
            $clientEntity->setGrant('client_credentials');
            $clientEntity->setAllowPlainTextPkce(true);
            return $clientEntity;
        }
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {

        if ($this->isGrantSupported($clientIdentifier, $grantType)) {
            return true;
        }

        if ($this->clientsTable->get($clientIdentifier)->get('secret') == $clientSecret) {
            return true;
        }
        return false;
    }


    private function isGrantSupported($clientIdentifier, $grantType): bool
    {
        $grants = $this->clientsTable->get($clientIdentifier)->get('grants');
        $explode = explode(" ", $grants);

        for ($i = 0; $i < count($explode); $i++) {
            if ($explode[$i] == $grantType) {
                return true;
            }
        }
        return false;
    }


}
