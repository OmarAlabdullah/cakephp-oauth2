<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * DbRefreshToken Model
 *
 * @property ClientsTable $Client
 * @property UsersTable $UsersTable
 */
class RefreshTokensTable extends Table {
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void {
        $this->setTable('refresh_tokens');
        $this->setPrimaryKey('identifier');
        $this->setDisplayField('access_token_id');
        $this->setDisplayField('expires_at');
        $this->setDisplayField('revoked');
        parent::initialize($config);
    }
}
