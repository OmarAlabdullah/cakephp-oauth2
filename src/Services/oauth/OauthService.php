<?php

namespace App\Services\oauth;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;

interface OauthService
{
    public function login(LoginRequest $loginRequest);

    public function register(RegisterRequest $registerRequest);

    public function changePassword(EmailRequest $emailRequest);

    public function logout(EmailRequest $emailRequest);

    public function find(EmailRequest $emailRequest);
}
