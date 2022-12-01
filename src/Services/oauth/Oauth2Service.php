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


    public function register(RegisterRequest $registerRequest): string
    {
        $this->userORM->saveUser($registerRequest->getEmail(), $registerRequest->getEmail(),$registerRequest->getPassword());
        return "user";


    }

    public function getUserBySAccessToken(array|string|null $token): UserEntityInterface {
        return $this->userORM->getUserEntityByAccessToken($token);
    }
}
