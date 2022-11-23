<?php

namespace App\Services\oauth;


use App\Domain\TokenRequest;

interface TokenServiceInterface {
    public function accessToken(TokenRequest $tokenRequest);

}
