<?php
declare(strict_types=1);
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="PHP IAM",
 *     version="1.0.0",
 *     description="A Template for a CakePHP Application. Specification used: [OpenApi 3](https://swagger.io/specification/)",
 *     description="Internal api-docs at [API Docs](/api-docs/index.html)",
 *     @OA\Contact(
 *         name="Xel Media BV",
 *         url="http://www.xel.nl/",
 *         email="support@xel.nl"
 *     )
 * )
 * @OA\Server(url="/v1")
 *
 * @OA\ExternalDocumentation(
 *     description="Check out the project README for more info",
 *     url="https://gitlab.xel.nl/xel-webservices/cakephp-project-template/-/blob/master/README.md"
 * )
 *
 * @OA\OpenApi(
 *     security={{"basicAuth": {} }}
 * )
 *
 * @OA\Header(
 *     header="accept: application/json",
 *     @OA\Schema(
 *         type="string"
 *     )
 * )
 *
 * @OA\Parameter(
 *     in="header",
 *     name="X-Xel-Services-RunLevel",
 *     description="Identifier for the environment used",
 *     required=true,
 *     @OA\Schema(
 *         type="string",
 *         enum={"local","dev","prod"}
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="basicAuth",
 *     type="http",
 *     scheme="basic",
 *     description="Basic HTTP Auth"
 *  )
 */
