<?php

namespace App\Orm;

use App\Model\Entity\User;
use App\Model\Table\ClientsTable;
use App\Model\Table\UsersTable;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Xel\Common\Exception\UnauthorizedException;

class UserORM implements UserRepositoryInterface {
    private UsersTable $usersTable;
    private ClientsTable $clientTable;
    private AccessTokenORM $accessTokenORM;

    /**
     * @param UsersTable $usersTable
     * @param ClientsTable $clientTable
     * @param AccessTokenORM $accessTokenORM
     */
    public function __construct(UsersTable $usersTable, ClientsTable $clientTable, AccessTokenORM $accessTokenORM) {
        $this->usersTable = $usersTable;
        $this->clientTable = $clientTable;
        $this->accessTokenORM = $accessTokenORM;
    }


    public function saveUser(string $email, string $username, string $password): void {

        $userEntity = new Entity([
            'email' => $email,
            'password' => $password,
            'username' => $username
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

    public function getUserEntityByAccessToken(string $token): UserEntityInterface {
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $userId = $jwtPayload->sub;
        $accessTokenId = $jwtPayload->jti;

        if ($this->accessTokenORM->isAccessTokenRevoked($accessTokenId)) {
            throw new UnauthorizedException('Access token is revoked');
        }

        $user = $this->usersTable->get($userId);
        $userEntity = new User();
        $userEntity->setIdentifier($user->get('identifier'));
        $userEntity->setEmail($user->get('email'));

        return $userEntity;
    }

}
