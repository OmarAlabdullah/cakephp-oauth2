<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\registerRequest;
use App\Services\oauth\OauthService;
use League\OAuth2\Server\AuthorizationServer;
use OpenApi\Annotations as OA;
use phpDocumentor\Transformer\Router\Router;
use Ray\Di\Di\Inject;

class LoginController extends AppController {
    protected OauthService $oauth2Service;
    private AuthorizationServer $server;
    private string $clientId = "00843";
    private string $clientSecret = "00843080de0839b3d29927e9c0881a51b2f359f4eeb7ab0f4b46b3abe7422934b1d3eb412e787ce5340769";
    private string $grantType = "password";
    public function initialize(): void {
        parent::initialize();
        $this->Auth->allow(['login']);
        $this->Auth->allow(['register']);
        $this->Auth->allow(['changePassword']);
        $this->Auth->allow(['find']);
    }

    /**
     * @Inject
     * @param OauthService $oauth2Service
     * @return void
     */
    public function inject(OauthService $oauth2Service,
                           AuthorizationServer $server) {
        $this->oauth2Service = $oauth2Service;
        $this->server = $server;
    }

    /**
     * @OA\Post  (
     *     path="/login",
     *     tags={"authentication"},
     *     description="Login/authenticate using credentials",
     *     @OA\Header(
     *          header="accept: application/json",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/X-Xel-Services-RunLevel"),
     *     @OA\RequestBody(
     *        required=true,
     *        description="Login credentials",
     *        @OA\JsonContent(
     *           ref="#/components/schemas/LoginRequest"
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login success",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent()
     *     )
     * )
     */



    public function login() {

        $this->setRequest($this->request->withData("grant_type", $this->grantType));
        $this->setRequest($this->request->withData("client_id", $this->clientId));
        $this->setRequest($this->request->withData("client_secret", $this->clientSecret));

        $response = $this->server->respondToAccessTokenRequest($this->request, $this->response);

        $response->getBody()->rewind();
        $json = $response->getBody()->getContents();
        $json = json_decode($json, true);

        /** @var LoginRequest $requestObject */
        $requestObject = $this->xelRequest->getDataAsDomainObject(LoginRequest::builder(), false);

        $query = http_build_query([
            'client_id' => $requestObject->getQueryClientId(),
            'redirect_uri' => $requestObject->getQueryRedirectUri(),
            'response_type' => $requestObject->getQueryResponseType(),
            'scope' => $requestObject->getQueryScope(),
            'access_token' => $json['access_token']
        ]);

        $redirect = "oauth/authorize?" . $query;

       return $this->redirect('https://php-oauth2.xel-localservices.nl/' . $redirect);

    }





    /**
     * @OA\Post  (
     *     path="/logout",
     *     tags={"authentication"},
     *     description="Logout/authenticate using credentials",
     *     @OA\Header(
     *          header="accept: application/json",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/X-Xel-Services-RunLevel"),
     *     @OA\RequestBody(
     *        required=true,
     *        description="Logout credentials",
     *        @OA\JsonContent(
     *           ref="#/components/schemas/EmailRequest"
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Logout success",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function logout() {
        /** @var EmailRequest $emailRequest */
        $emailRequest = $this->xelRequest->getDataAsDomainObject(EmailRequest::builder());
        $this->oauth2Service->logout($emailRequest);

    }
    /**
     * @OA\Post  (
     *     path="/register",
     *     tags={"authentication"},
     *     description="register/authenticate using credentials",
     *     @OA\Header(
     *          header="accept: application/json",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/X-Xel-Services-RunLevel"),
     *     @OA\RequestBody(
     *        required=true,
     *        description="register credentials",
     *        @OA\JsonContent(
     *           ref="#/components/schemas/RegisterRequest"
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Register success",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function register() {
        /** @var RegisterRequest $registerRequest */
        $registerRequest = $this->xelRequest->getDataAsDomainObject(RegisterRequest::builder());
        $user = $this->oauth2Service->register($registerRequest);
        $this->set("user", [
            'user' => $user
        ]);
    }

    /**
     * @OA\Post  (
     *     path="/change-password ",
     *     tags={"authentication"},
     *     description="change-password/authenticate using credentials",
     *     @OA\Header(
     *          header="accept: application/json",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/X-Xel-Services-RunLevel"),
     *     @OA\RequestBody(
     *        required=true,
     *        description="change password ",
     *        @OA\JsonContent(
     *           ref="#/components/schemas/EmailRequest"
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="change password success",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function changePassword() {
        /** @var EmailRequest $emailRequest */
        $emailRequest = $this->xelRequest->getDataAsDomainObject(EmailRequest::builder());
        $this->oauth2Service->changePassword($emailRequest);

    }

}
