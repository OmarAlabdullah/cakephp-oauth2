<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\LoginRequest;
use App\Domain\RegisterRequest;
use App\Services\oauth\OauthService;
use League\OAuth2\Server\AuthorizationServer;
use OpenApi\Annotations as OA;
use Ray\Di\Di\Inject;

class AuthenticationController extends AppController {
    protected OauthService $oauth2Service;
    private AuthorizationServer $server;
    private string $clientId = "login";
    private string $clientSecret = "login";
    private string $grantType = "password";

    public function initialize(): void {
        parent::initialize();
        $this->Auth->allow(['login']);
        $this->Auth->allow(['register']);
        $this->Auth->allow(['userInfo']);
    }

    /**
     * @Inject
     * @param OauthService $oauth2Service
     * @return void
     */
    public function inject(OauthService        $oauth2Service,
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


    public function login(): ?\Cake\Http\Response {

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
            'state' => $requestObject->getQueryState(),
            'access_token' => $json['access_token']
        ]);

        $host = $this->xelRequest->getRequest()->host();
        $redirect = "/oauth/authorize?" . $query;

        return $this->redirect("https://$host" . $redirect);


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
        $this->oauth2Service->register($registerRequest);

    }


    public function userInfo() {
        $accessToken = $this->request->getQuery('access_token');
        $user = $this->oauth2Service->userInfo($accessToken);

        $this->set(json_encode($user));
    }

}
