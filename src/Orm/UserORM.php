<?php

namespace App\Orm;

use App\Domain\LeagueEntities\User;
use App\Domain\UserClass;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Xel\Common\EntityConverter;

class UserORM implements UserRepositoryInterface
{
    private UsersTable $usersTable;

    public function __construct(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;

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
            throw new ConflictException("Cannot save user, Error: $t",  505, $t);
        }
    }


    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        try {
             $user = $this->usersTable->find()
                ->where(["email" => $username])
                ->firstOrFail();
             $userEntity = new User();
             $userEntity->setIdentifier($user->get('identifier'));

          return $userEntity;

        } catch (\Throwable $t) {
            throw new NotFoundException("user not found or $t");
        }
    }
}
