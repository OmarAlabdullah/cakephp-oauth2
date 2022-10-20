<?php

namespace App\Domain;

use Xel\Common\XelObject;

/**
 * Class LoginRequest
 *
 * // Setters
 * @method RegisterRequest setUsername (string $username)
 * @method RegisterRequest setPassword (string $password)
 *
 * // Getters
 * @method string getUsername
 * @method string getPassword
 *
 * @method static RegisterRequest builder
 * @method RegisterRequest build
 * @method RegisterRequest toBuilder
 *
 * @OA\Schema()
 */
class RegisterRequest extends XelObject {
    /** @OA\Property() */
    protected string $username;
    /** @OA\Property() */
    protected string $password;

    public function assertPassword(?string $password, string $msg = null): string
    {
        return $password;
    }
}
