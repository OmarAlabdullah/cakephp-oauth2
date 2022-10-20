<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\EmailRequest;
use App\Domain\LoginRequest;
use App\Domain\registerRequest;
use App\Services\IAM\IAMService;
use OpenApi\Annotations as OA;
use Ray\Di\Di\Inject;

class IAMController extends AppController {
    protected IAMService $iamService;

    public function initialize(): void {
        parent::initialize();
        $this->Auth->allow(['login']);
        $this->Auth->allow(['register']);
        $this->Auth->allow(['changePassword']);
    }

    /**
     * @Inject
     * @param IAMService $iamService
     * @return void
     */
    public function inject(IAMService $iamService) {
        $this->iamService = $iamService;
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
        /** @var LoginRequest $loginRequest */
        $loginRequest = $this->xelRequest->getDataAsDomainObject(LoginRequest::builder());
        $accessToken = $this->iamService->login($loginRequest);
        $this->set('access_token', $accessToken);
        $this->set('_serialize', ['access_token']);
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
        $accessToken = $this->iamService->logout($emailRequest);
        $this->set('access_token', $accessToken);
        $this->set('_serialize', ['access_token']);
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
        $this->iamService->register($registerRequest);

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
        $this->iamService->changePassword($emailRequest);

    }

}
