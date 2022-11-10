<?php

namespace App\Domain;

use Xel\Common\XelObject;

/**
 * Class LoginRequest
 *
 * // Setters
 * @method LoginRequest setUsername (string $username)
 * @method LoginRequest setPassword (string $password)
 *
 * // Getters
 * @method string getUsername
 * @method string getPassword
 *
 * @method static LoginRequest builder
 * @method LoginRequest build
 * @method LoginRequest toBuilder
 *
 * @OA\Schema()
 */
class LoginRequest extends XelObject
{
    /** @OA\Property() */
    protected string $username;
    /** @OA\Property() */
    protected string $password;

    public function assertPassword(?string $password, string $msg = null): string
    {
        return $password;
    }
}
