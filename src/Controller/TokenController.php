<?php
declare(strict_types=1);

namespace App\Controller;


use App\Domain\TokenRequest;
use App\Services\oauth\TokenService;
use App\Services\oauth\TokenServiceInterface;


use Cake\Event\Event;
use Cake\Http\Client\Response;
use Cake\Http\Exception\ForbiddenException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Ray\Di\Di\Inject;
use OpenApi\Annotations as OA;

use Xel\Common\Exception\UnauthorizedException;

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
                            AuthorizationServer $server
    ) {
        $this->tokenService = $tokenService;
        $this->server = $server;
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

    public function accessToken(): ResponseInterface
    {
        try {
            $response = $this->server->respondToAccessTokenRequest($this->request, $this->response);
        } catch (OAuthServerException $e) {

            throw new UnauthorizedException($e->getMessage());
        }
        return $response;

    }
}
