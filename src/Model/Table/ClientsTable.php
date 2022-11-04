<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Model\Table\AccessTokensTable;
use App\Model\Table\RefreshTokensTable;

/**
 * Client Model
 *
 * @property AccessTokensTable $AccessToken
 * @property AuthorizationCodeTable $AuthCode
 * @property RefreshTokensTable $DbRefreshToken
 */
class ClientsTable extends Table
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('clients');
        $this->setPrimaryKey('identifier');
        $this->setDisplayField('user_id');
        $this->setDisplayField('name');
        $this->setDisplayField('secret');
        $this->setDisplayField('provider');
        $this->setDisplayField('redirect');
        $this->setDisplayField('revoked');
        $this->setDisplayField('created_at');
        $this->setDisplayField('updated_at');
//        $this->hasMany('Sessions', [
//            'className' => 'OAuthServer.Sessions',
//            'foreignKey' => 'client_id'
//        ]);
        parent::initialize($config);
    }


}
