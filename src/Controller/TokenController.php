<?php
declare(strict_types=1);

namespace App\Controller;


use App\Services\oauth\TokenServiceInterface;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;


class TokenController extends AppController {
    protected TokenServiceInterface $tokenService;
    private AuthorizationServer $server;

    public function initialize(): void {
        parent::initialize();
        $this->Auth->allow(['accessToken']);
    }

    /**
     * @Inject
     * @param TokenServiceInterface $tokenService
     * @return void
     */
    public function inject(TokenServiceInterface $tokenService,
                           AuthorizationServer   $server
    ) {
        $this->tokenService = $tokenService;
        $this->server = $server;
    }

    /**
     * @OA\Post(
     *     path="/token",
     *     tags={"authentication"},
     *     description="Token/authenticate using credentials",
     *     @OA\Parameter(ref="#/components/parameters/X-Xel-Services-RunLevel"),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="username",
     *                      type="string",
     *                      description="username"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="password"
     *                  ),
     *                  @OA\Property(
     *                      property="grant_type",
     *                      type="string",
     *                      description="grant type",
     *                      enum={"password", "authentication_code", "refresh_token", "client_credentials"}
     *                  ),
     *                  @OA\Property(
     *                      property="client_id",
     *                      type="string",
     *                      description="client id"
     *                  )
     *              )
     *          )
     *      ),
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

    public function accessToken(): ResponseInterface {
        return $this->server->respondToAccessTokenRequest($this->request, $this->response);
    }
}
