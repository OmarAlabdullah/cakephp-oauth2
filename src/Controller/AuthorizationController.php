<?php
declare(strict_types=1);

namespace App\Controller;


use App\Domain\LeagueEntities\User;
use App\Services\oauth\Oauth2Service;
use App\Services\oauth\OauthService;
use App\Services\oauth\TokenService;
use App\Services\oauth\TokenServiceInterface;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Ray\Di\Di\Inject;
use OpenApi\Annotations as OA;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AuthorizationController extends AppController {
    protected OauthService $OauthService;
    private AuthorizationServer $server;

    public function initialize(): void {
        parent::initialize();
        $this->Auth->allow(['authorize']);
    }

    /**
     * @Inject
     * @param TokenServiceInterface $tokenService
     * @return void
     */




    public function inject(OauthService $OauthService,
                           AuthorizationServer $server,
    ) {
        $this->OauthService = $OauthService;
        $this->server = $server;
    }

    /**
     * @OA\Post(
     *     path="/authorize",
     *     tags={"authentication"},
     *     description="authorize/authenticate using credentials",
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
     *         description=" authorize",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent()
     *     )
     * )
     */

    public function authorize(): ResponseInterface
    {
        try {
            $authRequest = $this->server->validateAuthorizationRequest($this->request);


            $authRequest->setAuthorizationApproved(true);
             $authRequest->setUser($this->OauthService->getUserById($this->request->getQuery('user_id')));

            $response = $this->server->completeAuthorizationRequest($authRequest, $this->response);
            //var_dump($response); die();
        } catch (OAuthServerException $e) {
            throw $e;
            //          throw new UnauthorizedException($e->getMessage());
        }
        return $response;

    }
}
