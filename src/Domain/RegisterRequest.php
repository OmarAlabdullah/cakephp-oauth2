<?php

namespace App\Domain;

use Xel\Common\XelObject;

/**
 * Class LoginRequest
 *
 * // Setters
 * @method RegisterRequest setEmail(string $email)
 * @method RegisterRequest setPassword (string $password)
 *
 * // Getters
 * @method string getEmail
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
    protected string $email;
    /** @OA\Property() */
    protected string $password;

    public function assertPassword(?string $password, string $msg = null): string
    {
        return $password;
    }
}
