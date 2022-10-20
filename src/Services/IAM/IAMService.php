<?php

namespace App\Services\IAM;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;

interface IAMService {
    public function login(LoginRequest $loginRequest);
    public function register(RegisterRequest $registerRequest);
    public function changePassword(EmailRequest $emailRequest);
    public function logout(EmailRequest $emailRequest);
}
