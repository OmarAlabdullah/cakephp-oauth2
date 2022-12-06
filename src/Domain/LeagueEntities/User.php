<?php

declare(strict_types=1);

namespace App\Domain\LeagueEntities;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

final class User implements UserEntityInterface {
    use EntityTrait;

    /**
     * @var string
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }


}
