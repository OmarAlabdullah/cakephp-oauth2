<?php
declare(strict_types=1);

namespace App\Controller;


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
        $this->Auth->allow(['authorize']);

    }

    /**
     * @Inject
     * @param OauthService $OauthService
     * @param AuthorizationServer $server
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

    public function authorize(): ResponseInterface {


        $query = http_build_query([
            'client_id' => $this->request->getQuery('client_id'),
            'redirect_uri' => $this->request->getQuery('redirect_uri'),
            'response_type' => $this->request->getQuery('response_type'),
            'scope' => $this->request->getQuery('scope'),
            'state' => $this->request->getQuery('state'),

        ]);


        if ($this->request->getQuery('access_token') == null){

           return $this->redirect("https://php-oauth2.xel-localservices.nl/login?". $query);
        }
        $authRequest = $this->server->validateAuthorizationRequest($this->request);

        $authRequest->setUser($this->OauthService->getUserBySAccessToken($this->request->getQuery('access_token')));
        $authRequest->setAuthorizationApproved(true);



        return $this->server->completeAuthorizationRequest($authRequest, $this->response);

    }
}
