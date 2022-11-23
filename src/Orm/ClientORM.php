<?php

namespace App\Orm;

use App\Domain\LeagueEntities\Client;
use App\Model\Table\ClientsTable;
use Cake\Datasource\EntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientORM implements ClientRepositoryInterface {

    private ClientsTable $clientsTable;

    public function __construct(ClientsTable $clientsTable) {
        $this->clientsTable = $clientsTable;
    }

    public function getClientEntity($clientIdentifier): ClientEntityInterface {

        $client = $this->clientsTable->get($clientIdentifier);
        $clientEntity = new Client();
        $clientEntity->setIdentifier($client->get('identifier'));
        $clientEntity->setConfidential($client->get('isConfidential'));
        $clientEntity->setGrant($client->get('grants'));
        $clientEntity->setName($client->get('name'));
        $clientEntity->setRedirectUri($this->stringConverterToArray($client->get('redirect')));
        $clientEntity->setAllowPlainTextPkce($client->get('allow_plain_text_pkce'));

        return $clientEntity;

    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool {
        $client = $this->clientsTable->get($clientIdentifier);

        if ($this->isGrantSupported($client, $grantType) &&
            $client->get('secret') == $clientSecret) {
            return true;
        }
        return false;
    }


    private function isGrantSupported(EntityInterface $client, string $grantType): bool {
        $grants = $client->get('grants');
        $explode = $this->stringConverterToArray($grants);

        return in_array($grantType, $explode);

    }


    private function stringConverterToArray($string): array {
        return explode(" ", $string);
    }

}
