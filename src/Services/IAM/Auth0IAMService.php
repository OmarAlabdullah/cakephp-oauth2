<?php

namespace App\Services\IAM;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;
use Auth0\SDK\Auth0;
use Auth0\SDK\JWTVerifier;
use Auth0\SDK\Helpers\Cache\FileSystemCacheHandler;
use Xel\Common\Exception\ServiceException;
use Xel\Driver\Response;
use Xel\Driver\ResponseHandler;

class Auth0IAMService implements IAMService {

    private Auth0 $auth0;

    public function __construct(Auth0 $auth0) {
        $this->auth0 = $auth0;
    }





    public function login(LoginRequest $loginRequest) :string {
        $realm = "Username-Password-Authentication";
        $response = $this->auth0->authentication()->login($loginRequest->getUsername(), $loginRequest->getPassword(), $realm);
        $json = json_decode($response->getBody()->getContents(), true);
        print_r($json);die();
        return $json['access_token'];
    }

    public function register(RegisterRequest $registerRequest): string {
        $response = $this->auth0->authentication()->dbConnectionsSignup($registerRequest->getUsername(), $registerRequest->getPassword(),'Username-Password-Authentication');
        if($response->getStatusCode() > 299 || $response->getStatusCode() < 200) {
            throw new ServiceException("Error registering user: " . $response->getReasonPhrase(), $response->getStatusCode());
        }

        $json = json_decode($response->getBody()->getContents(), true);
        print_r($json);die();
        return "uw krijgt een mail om te v";
    }

    public function changePassword(EmailRequest $emailRequest) :Response {
        $response = $this->auth0->authentication()->dbConnectionsChangePassword($emailRequest->getEmail(),'Username-Password-Authentication');
        $json = json_decode($response->getBody()->getContents(), true);
        print_r($json);die();
        return $response;
    }

    public function logout(EmailRequest $emailRequest) :Response {
        $response = $this->auth0->authentication()->getLogoutLink('https://php-iam.xel-localservices.nl/',null);
        $json = json_decode($response->getBody()->getContents(), true);
        print_r($json);die();

    }


}
