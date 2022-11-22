<?php

namespace App\Orm;

use App\Domain\LeagueEntities\Client;
use App\Model\Table\ClientsTable;
use League\Container\Exception\NotFoundException;
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
        try {
            $client = $this->clientsTable->get($clientIdentifier);
            $clientEntity = new Client();
            $clientEntity->setIdentifier($client->get('identifier'));
            $clientEntity->setConfidential($client->get('isConfidential'));
            $clientEntity->setGrant($client->get('grants'));
            $clientEntity->setName($client->get('name'));
            $clientEntity->setRedirectUri($this->stringConverterToArray($client->get('redirect')));
            $clientEntity->setAllowPlainTextPkce($client->get('allow_plain_text_pkce'));

            return $clientEntity;

        } catch (\Throwable $t) {
            throw new NotFoundException("data is not correct , $t");
        }


    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {

        if ($this->isGrantSupported($clientIdentifier, $grantType) &&
            $this->clientsTable->get($clientIdentifier)->get('secret') == $clientSecret) {
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


    private function stringConverterToArray($string): array
    {
        return explode(" ", $string);
    }



}
