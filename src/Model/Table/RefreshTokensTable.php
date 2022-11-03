<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * RefreshToken Model
 *
 * @property ClientsTable $Client
 * @property UsersTable $UsersTable
 */
class RefreshTokensTable extends Table
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('refresh_tokens');
        $this->setPrimaryKey('id');
        $this->setDisplayField('access_token_id');
        $this->setDisplayField('revoked');
        $this->setDisplayField('expires_at');
//        $this->hasMany('AccessTokensTable', [
//            'className' => 'AccessTokensTable',
//            'foreignKey' => 'oauth_token'
//        ]);
        parent::initialize($config);
    }
}
