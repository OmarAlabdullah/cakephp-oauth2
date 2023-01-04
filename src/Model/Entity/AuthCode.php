<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

final class AuthCode extends Entity implements AuthCodeEntityInterface {
    use AuthCodeTrait;
    use EntityTrait;
    use TokenEntityTrait;
}
