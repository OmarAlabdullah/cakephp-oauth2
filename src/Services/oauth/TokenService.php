<?php

namespace App\Services\oauth;



use App\Domain\TokenRequest;

class TokenService implements TokenServiceInterface {

    public function accessToken(TokenRequest $tokenRequest): string
    {
        return (string) $tokenRequest->getEmail();
    }
}
