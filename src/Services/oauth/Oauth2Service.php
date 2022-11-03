<?php

namespace App\Services\oauth;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;
use App\Model\Table\UsersTable;
use App\Orm\UserORM;

class Oauth2Service implements OauthService {

    private UserORM $userORM;

    public function __construct(UserORM $userORM) {
        $this->userORM = $userORM;
    }





    public function login(LoginRequest $loginRequest) :string {

//        $realm = "Username-Password-Authentication";
//        $response = $this->auth0->authentication()->login($loginRequest->getUsername(), $loginRequest->getPassword(), $realm);
//        $json = json_decode($response->getBody()->getContents(), true);
//        print_r($json);die();
//        return $json['access_token'];
        return "heeeeeee";
    }

    public function register(RegisterRequest $registerRequest): string {
        $this->userORM->register($registerRequest);
//        if($response->getStatusCode() > 299 || $response->getStatusCode() < 200) {
//            throw new ServiceException("Error registering user: " . $response->getReasonPhrase(), $response->getStatusCode());
        return "heeeeeee";


    }

    public function changePassword(EmailRequest $emailRequest) :string {
//        $response = $this->auth0->authentication()->dbConnectionsChangePassword($emailRequest->getEmail(),'Username-Password-Authentication');
//        $json = json_decode($response->getBody()->getContents(), true);
//        print_r($json);die();
//        return $response;
        return "heeeeeee";
    }

    public function logout(EmailRequest $emailRequest) :string {
      return "";

    }

    public function sso(EmailRequest $emailRequest) :string {

        return "response";
    }


}
