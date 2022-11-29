<?php

namespace App\Orm;

use App\Domain\LeagueEntities\User;
use App\Model\Table\ClientsTable;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Entity;
use Lcobucci\JWT\Token;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserORM implements UserRepositoryInterface {
    private UsersTable $usersTable;
    private ClientsTable $clientTable;

    /**
     * @param UsersTable $usersTable
     * @param ClientsTable $clientTable
     */
    public function __construct(UsersTable $usersTable, ClientsTable $clientTable) {
        $this->usersTable = $usersTable;
        $this->clientTable = $clientTable;
    }


    public function saveUser(string $email, string $password): void {
        $userEntity = new Entity([
            'email' => $email,
            'password' => $password
        ]);

        $this->usersTable->saveOrFail($userEntity);
    }


    public function getUserEntityByUserCredentials($username, $password, $grantType,
                                                   ClientEntityInterface $clientEntity): UserEntityInterface {

        $this->clientTable->get($clientEntity->getIdentifier());

        $user = $this->usersTable->find()
            ->where(["username" => $username,
                "password" => $password])
            ->firstOrFail();
        $userEntity = new User();
        $userEntity->setIdentifier($user->get('identifier'));
        $userEntity->setEmail($user->get('email'));

        return $userEntity;
    }

    public function getUserEntityByAccessToken(Token $accessToken): UserEntityInterface {
        $userId = $accessToken->headers()->get("id");
        $user = $this->usersTable->get($userId);
        $userEntity = new User();
        $userEntity->setIdentifier($user->get('identifier'));
        $userEntity->setEmail($user->get('email'));

        return $userEntity;
    }

}
