<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

final class Client extends Entity implements ClientEntityInterface {
    use ClientTrait;
    use EntityTrait;

    /**
     * @var bool
     */
    private bool $allowPlainTextPkce = false;

    /**
     * @var string
     */
    private string $grant;

    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @param string[] $redirectUri
     */
    public function setRedirectUri(array $redirectUri): void {
        $this->redirectUri = $redirectUri;
    }

    public function setConfidential(bool $isConfidential): void {
        $this->isConfidential = $isConfidential;
    }

    public function isPlainTextPkceAllowed(): bool {
        return $this->allowPlainTextPkce;
    }

    public function setAllowPlainTextPkce(bool $allowPlainTextPkce): void {
        $this->allowPlainTextPkce = $allowPlainTextPkce;
    }

    public function setGrant(string $grant): void {
        $this->grant = $grant;
    }
}
