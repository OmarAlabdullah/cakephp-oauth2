<?php

namespace App\Domain;

use OpenApi\Annotations as OA;
use Xel\Common\XelObject;

/**
 * Class UserObject
 *
 * // Setters
 * @method UserObject setEmail(string $email)
 * @method UserObject setId (string $id)
 *
 * // Getters
 * @method string getEmail
 * @method string getId
 *
 * @method static UserObject builder
 * @method UserObject build
 * @method UserObject toBuilder
 *
 * @OA\Schema()
 */
class UserObject extends XelObject {
    /** @OA\Property() */
    protected string $email;
    /** @OA\Property() */
    protected int $id;

}
