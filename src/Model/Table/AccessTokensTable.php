<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * AccessToken Model
 *
 * @property ClientsTable $clientsTable
 * @property UsersTable $UsersTable
 */
class AccessTokensTable extends Table
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('access_tokens');
        $this->setPrimaryKey('identifier');
        $this->setDisplayField('user_id');
        $this->setDisplayField('client_id');
        $this->setDisplayField('scopes');
        $this->setDisplayField('revoked');
        $this->setDisplayField('expires_at');
//        $this->hasMany('ClientTable', [
//            'className' => 'OAuthServer.AccessTokenScopes',
//            'foreignKey' => 'oauth_token',
//            'dependant' => true
//        ]);
        parent::initialize($config);
    }
}
