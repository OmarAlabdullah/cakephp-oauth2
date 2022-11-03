<?php
declare(strict_types=1);

namespace App\Controller;


use App\Services\oauth\TokenService;
use App\Services\oauth\TokenServiceInterface;
use OAuth2\Request;
use Ray\Di\Di\Inject;
use OpenApi\Annotations as OA;

class TokenController extends AppController {
    protected TokenServiceInterface $tokenService;

    public function initialize(): void {
        parent::initialize();
        $this->Auth->allow(['accessToken']);
    }

    /**
     * @Inject
     * @param TokenServiceInterface $tokenService
     * @return void
     */
    public function inject(TokenServiceInterface $tokenService) {
        $this->tokenService = $tokenService;
    }

    /**
     * @OA\Post(
     *     path="/token",
     *     tags={"authentication"},
     *     description="Token/authenticate using credentials",
     *     @OA\Header(
     *          header="accept: application/json",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/X-Xel-Services-RunLevel"),
     *     @OA\RequestBody(
     *        required=true,
     *        description="Login credentials",
     *        @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login via token success",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function accessToken() {
        $token = $this->tokenService->accessToken(Request::createFromGlobals());
        $this->set("token", [
            'accessToken' => $token
        ]);
        $this->viewBuilder()->setOption('serialize', 'token');
    }
}
