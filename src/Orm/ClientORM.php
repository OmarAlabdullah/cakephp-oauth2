<?php

namespace App\Orm;

use App\Model\Table\ClientsTable;
use Cake\Http\Exception\NotFoundException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientORM implements ClientRepositoryInterface
{

    private ClientsTable $clientsTable;

    public function __construct(ClientsTable $clientsTable)
    {
        $this->clientsTable = $clientsTable;
    }

    public function getClientEntity($clientIdentifier){
        {
            try {
                return $this->clientsTable->find()
                    ->where(["identifier" => $clientIdentifier])
                    ->firstOrFail();
            } catch (\Throwable $t) {
                throw new NotFoundException("client not found");
            }

        }
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType){
         $this->clientsTable->get($clientIdentifier);

        return true;
    }
}
