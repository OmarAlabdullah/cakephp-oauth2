<?php

namespace App\Services\oauth;

use OAuth2\Request;

class TokenService implements TokenServiceInterface {

    public function accessToken(Request $request): string
    {
        return "token...........";
    }
}
