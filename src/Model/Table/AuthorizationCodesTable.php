<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use App\Model\Table\UsersTable;

/**
 * AuthCode Model
 *
 * @property ClientsTable $Client
 * @property UsersTable $UsersTable
 */
class AuthorizationCodesTable extends Table
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('authorization_codes');
        $this->setPrimaryKey('identifier');
        $this->setDisplayField('user_id');
        $this->setDisplayField('client_id');
        $this->setDisplayField('scopes');
        $this->setDisplayField('revoked');
        $this->setDisplayField('expires_at');

//        $this->belongsTo(
//            'Sessions',
//            [
//                'className' => 'OAuthServer.Sessions',
//                'foreignKey' => 'session_id'
//            ]
//        );
//        $this->hasMany(
//            'AuthCodeScopes',
//            [
//                'className' => 'OAuthServer.AuthCodeScopes',
//                'foreignKey' => 'auth_code',
//                'dependant' => true
//            ]
//        );
        parent::initialize($config);
    }
}
