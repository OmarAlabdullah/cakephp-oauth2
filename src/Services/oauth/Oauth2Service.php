<?php

namespace App\Services\oauth;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;
use App\Orm\ClientORM;
use App\Orm\UserORM;
use League\OAuth2\Server\Entities\UserEntityInterface;

class Oauth2Service implements OauthService
{

    private UserORM $userORM;
    private ClientORM $clientORM;

    /**
     * @param UserORM $userORM
     * @param ClientORM $clientORM
     */
    public function __construct(UserORM $userORM, ClientORM $clientORM) {
        $this->userORM = $userORM;
        $this->clientORM = $clientORM;
    }


    public function login(LoginRequest $loginRequest): string
    {

        return "heeeeeee";
    }

    public function register(RegisterRequest $registerRequest): string
    {
        $this->userORM->saveUser($registerRequest);
        return "user";


    }

    public function changePassword(EmailRequest $emailRequest): string
    {

        return "heeeeeee";
    }

    public function logout(EmailRequest $emailRequest): string
    {
        return "";

    }

    public function find(EmailRequest $emailRequest): string
    {
        return "";
    }

    public function getUserByCredentials(string $username, string $password, string $grantType, string $clientId): UserEntityInterface
    {
        $client = $this->clientORM->getClientEntity($clientId);

        return $this->userORM->getUserEntityByUserCredentials($username,$password, $grantType, $client);

    }
}
