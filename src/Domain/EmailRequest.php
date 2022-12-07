<?php

namespace App\Domain;

use OpenApi\Annotations as OA;
use Xel\Common\XelObject;

/**
 * Class EmailRequest
 *
 * // Setters
 * @method EmailRequest setEmail (string $email)
 *
 * // Getters
 * @method string getEmail
 *
 * @method static EmailRequest builder
 * @method EmailRequest build
 * @method EmailRequest toBuilder
 *
 * @OA\Schema()
 */
class EmailRequest extends XelObject {
    /** @OA\Property() */
    protected string $email;


}
