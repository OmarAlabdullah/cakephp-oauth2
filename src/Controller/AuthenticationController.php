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


    public function initialize(): void {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['login', 'register', 'userInfo']);

    }

    /**
     * @Inject
     * @param OauthService $oauth2Service
     * @return void
     */
    public function inject(OauthService $oauth2Service) {
        $this->oauth2Service = $oauth2Service;

    }

    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'register', 'userInfo']);

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

        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        /** @var LoginRequest $requestObject */
        $requestObject = $this->xelRequest->getDataAsDomainObject(LoginRequest::builder(), false);
        $queryArray = [
            'client_id' => $requestObject->getQueryClientId(),
            'redirect_uri' => $requestObject->getQueryRedirectUri(),
            'response_type' => $requestObject->getQueryResponseType(),
            'scope' => $requestObject->getQueryScope(),
            'state' => $requestObject->getQueryState()
        ];

        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $queryArray['email'] = $result->getData()->offsetGet('email');
            $queryArray['password'] = $result->getData()->offsetGet('password');

            $query = http_build_query($queryArray);

            // redirect to /authorize after login success
            $host = $this->xelRequest->getRequest()->host();
            $redirect = "/oauth/authorize?" . $query;

            // save username in session if login succeeded to use it in the frontend
            $_SESSION["username"] = $result->getData()->offsetGet('email');

            return $this->redirect("https://$host" . $redirect);
        }

        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
            return $this->redirect('/login?' . http_build_query($queryArray));
        }
    }


    public function register() {

        /** @var RegisterRequest $registerRequest */
        $registerRequest = $this->xelRequest->getDataAsDomainObject(RegisterRequest::builder());
        $this->oauth2Service->register($registerRequest);

    }


    public function userInfo() {

        $accessToken = $this->request->getQuery('access_token');

        $user = $this->oauth2Service->userInfo($accessToken);

        return $this->response->withStringBody(json_encode($user));


    }

    public function logout() {

        $this->Authentication->logout();

        // delete username from session if user logged out
        $_SESSION["username"] = "";

        $this->Flash->success(__('You successfully logged out.'));

        // user will be redirect to login page after logging out with all the parameters that were in the query
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
