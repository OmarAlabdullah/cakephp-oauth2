<?php

namespace App\Orm;

use App\Model\Entity\Client;
use App\Model\Table\ClientsTable;
use Cake\Http\Exception\NotFoundException;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientORM implements ClientRepositoryInterface {

    private ClientsTable $clientsTable;

    public function __construct(ClientsTable $clientsTable)
    {
        $this->clientsTable = $clientsTable;
    }

    public function getClientEntity($clientIdentifier): ClientEntityInterface{
        {
            $clientEntity = new Client();
            $clientEntity->setIdentifier($clientIdentifier);
            $clientEntity->setGrant('client_credentials');
            $clientEntity->setAllowPlainTextPkce(true);
            return $clientEntity;
        }
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType){
         $this->clientsTable->get($clientIdentifier);

        return true;
    }
}
