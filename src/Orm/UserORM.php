<?php

namespace App\Orm;

use App\Domain\LeagueEntities\User;
use App\Model\Table\ClientsTable;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserORM implements UserRepositoryInterface
{
    private UsersTable $usersTable;
    private ClientsTable $clientTable;

    /**
     * @param UsersTable $usersTable
     * @param ClientsTable $clientTable
     */
    public function __construct(UsersTable $usersTable, ClientsTable $clientTable)
    {
        $this->usersTable = $usersTable;
        $this->clientTable = $clientTable;
    }


    public function saveUser(string $email, string $password): void
    {
        $userEntity = new Entity([
            'email' => $email,
            'password' => $password
        ]);

        try {
            $this->usersTable->saveOrFail($userEntity);
        } catch (\Exception $t) {
            throw new ConflictException("Cannot save user, Error: $t", 505, $t);
        }
    }


    public function getUserEntityByUserCredentials($username, $password, $grantType,
                                                   ClientEntityInterface $clientEntity): UserEntityInterface
    {
        try {
            $user = $this->usersTable->find()
                ->where(["username" => $username,
                    "password" => $password])
                ->firstOrFail();
            $userEntity = new User();
            $userEntity->setIdentifier($user->get('identifier'));

            $this->clientTable->get($clientEntity->getIdentifier());

            return $userEntity;

        } catch (\Throwable $t) {
            throw new NotFoundException("data is not correct, $t");
        }
    }

    public function getUserById(string $userId): UserEntityInterface
    {
        try {
            $user = $this->usersTable->get($userId);
            $userEntity = new User();
            $userEntity->setIdentifier($user->get('identifier'));


            return $userEntity;

        } catch (\Throwable $t) {
            throw new NotFoundException("user not found $t");
        }
    }
}
