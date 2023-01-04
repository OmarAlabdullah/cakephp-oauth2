<?php
declare(strict_types=1);

namespace App\Controller;


use App\Model\Entity\User;
use App\Services\oauth\OauthService;
use League\OAuth2\Server\AuthorizationServer;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface;
use Ray\Di\Di\Inject;

class AuthorizationController extends AppController {
    protected OauthService $OauthService;
    private AuthorizationServer $server;


    public function initialize(): void {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['authorize']);

    }

    /**
     * @Inject
     * @param OauthService $OauthService
     * @param AuthorizationServer $server
     * @return void
     */


    public function inject(OauthService        $OauthService,
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

    public function authorize(): ResponseInterface {

        $authRequest = $this->server->validateAuthorizationRequest($this->request);

        $query = http_build_query($this->request->getQueryParams());

        if ($this->request->getQuery('email') == null) {

            $host = $this->xelRequest->getRequest()->host();
            return $this->redirect("https://$host/login?" . $query);
        }


        $authRequest->setUser($this->OauthService->getUserByEmailAndPassword($this->request->getQuery('email'),
            $this->request->getQuery('password')));

        $authRequest->setAuthorizationApproved(true);

        return $this->server->completeAuthorizationRequest($authRequest, $this->response);

    }
}
