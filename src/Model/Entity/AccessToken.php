<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

final class AccessToken extends Entity implements AccessTokenEntityInterface {
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;
}
