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
        $this->setPrimaryKey('id');
        $this->setDisplayField('user_id');
        $this->setDisplayField('client_id');
        $this->setDisplayField('name');
        $this->setDisplayField('scopes');
        $this->setDisplayField('revoked');
        $this->setDisplayField('created_at');
        $this->setDisplayField('updated_at');
        $this->setDisplayField('expires_at');
//        $this->hasMany('ClientTable', [
//            'className' => 'OAuthServer.AccessTokenScopes',
//            'foreignKey' => 'oauth_token',
//            'dependant' => true
//        ]);
        parent::initialize($config);
    }
}
