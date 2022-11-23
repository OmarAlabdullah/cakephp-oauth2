<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @psalm-immutable
 */
class ScopeModel {
    /**
     * @var string
     */
    private $scope;

    /**
     * @psalm-mutation-free
     */
    public function __construct(string $scope) {
        $this->scope = $scope;
    }

    /**
     * @psalm-mutation-free
     */
    public function __toString(): string {
        return $this->scope;
    }
}
