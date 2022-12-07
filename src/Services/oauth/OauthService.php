<?php

namespace App\Services\oauth;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;

interface OauthService {

    public function register(RegisterRequest $registerRequest);

    public function getUserBySAccessToken(array|string|null $token);
}
