<?php

namespace App\Services\oauth;

use App\Domain\RegisterRequest;
use App\Domain\UserObject;
use App\Orm\UserORM;
use League\OAuth2\Server\Entities\UserEntityInterface;

class Oauth2Service implements OauthService {

    private UserORM $userORM;

    /**
     * @param UserORM $userORM
     */
    public function __construct(UserORM $userORM) {
        $this->userORM = $userORM;
    }


    public function register(RegisterRequest $registerRequest): string {
        $this->userORM->saveUser($registerRequest->getEmail(), $registerRequest->getEmail(), $registerRequest->getPassword());
        return "user";


    }

    public function getUserBySAccessToken(array|string|null $token): UserEntityInterface {
        return $this->userORM->getUserEntityByAccessToken($token);
    }

    public function userInfo(string $accessToken): UserObject {
        $userEntity =  $this->userORM->getUserEntityByAccessToken($accessToken);
        return UserObject::builder()
            ->setEmail($userEntity->getEmail())
            ->setId($userEntity->getIdentifier())
            ->build();

    }
}
