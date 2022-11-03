<?php

namespace App\Services\oauth;

use App\Domain\EmailRequest;
use OAuth2\Request;

interface TokenServiceInterface
{
    public function accessToken(Request $request);

}
