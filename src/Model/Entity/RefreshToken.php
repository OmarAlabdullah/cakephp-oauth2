<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

final class RefreshToken extends Entity implements RefreshTokenEntityInterface {
    use EntityTrait;
    use RefreshTokenTrait;
}
