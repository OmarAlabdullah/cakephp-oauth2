<?php

namespace App\Services\oauth;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;
use App\Orm\UserORM;
use League\OAuth2\Server\Entities\UserEntityInterface;

class Oauth2Service implements OauthService {

    private UserORM $userORM;

    public function __construct(UserORM $userORM) {
        $this->userORM = $userORM;
    }


    public function login(LoginRequest $loginRequest): string {

        return "heeeeeee";
    }

    public function register(RegisterRequest $registerRequest): string {
        $this->userORM->saveUser($registerRequest);
        return "user";


    }

    public function changePassword(EmailRequest $emailRequest): string {

        return "heeeeeee";
    }

    public function logout(EmailRequest $emailRequest): string {
        return "";

    }

    public function find(EmailRequest $emailRequest): string {
        return "";
    }

    public function getUserByCredentials(string $username, string $password): UserEntityInterface {
        return $this->userORM->getUserByCredentials($username, $password);

    }
}
