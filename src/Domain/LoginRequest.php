<?php

namespace App\Domain;

use Xel\Common\XelObject;


/**
 * Class LoginRequest
 *
 * // Setters
 * @method LoginRequest setQueryClientId (string $queryClientId)
 * @method LoginRequest setQueryRedirectUri (string $queryRedirectUri)
 * @method LoginRequest setQueryResponseType (string $queryResponseType)
 * @method LoginRequest setQueryScope (string $queryScope)
 * @method LoginRequest setQueryState (string $queryState)
 *
 * // Getters
 * @method string getQueryClientId
 * @method string getQueryRedirectUri
 * @method string getQueryResponseType
 * @method string getQueryScope
 * @method string getQueryState
 *
 * @method static LoginRequest builder
 * @method LoginRequest build
 * @method LoginRequest toBuilder
 *
 *
 */
class LoginRequest extends XelObject {
    protected string $queryClientId;
    protected string $queryRedirectUri;
    protected string $queryResponseType;
    protected string $queryScope;
    protected string $queryState;
}
