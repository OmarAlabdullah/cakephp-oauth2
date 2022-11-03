<?php

namespace App\Domain;

use phpseclib3\File\ASN1\Maps\Time;
use Xel\Common\XelObject;

/**
 * Class LoginRequest
 *
 * // Setters
 * @method TokenRequest setEmail(string $email)
 * @method TokenRequest setPassword (string $password)
 *
 * // Getters
 * @method string getEmail
 * @method string getPassword
 *
 * @method static TokenRequest builder
 * @method TokenRequest build
 * @method TokenRequest toBuilder
 *
 * @OA\Schema()
 */
class TokenRequest extends XelObject {
    /** @OA\Property() */
    protected string $id;
    /** @OA\Property() */
    protected string $userId;
    /** @OA\Property() */
    protected string $clientId;
    /** @OA\Property() */
    protected string $name;
    /** @OA\Property() */
    protected string $scopes;
    /** @OA\Property() */
    protected int $revoked;
    /** @OA\Property() */
    protected Time $createdAt;
    /** @OA\Property() */
    protected Time $updatedAt;
    /** @OA\Property() */
    protected Time $expiresAt;


}
