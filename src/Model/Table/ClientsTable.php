<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Client Model
 *
 * @property AccessTokensTable $AccessToken
 * @property AuthorizationCodesTable $AuthCode
 * @property RefreshTokensTable $DbRefreshToken
 */
class ClientsTable extends Table {
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void {
        $this->setTable('clients');
        $this->setPrimaryKey('identifier');
        $this->setDisplayField('user_id');
        $this->setDisplayField('name');
        $this->setDisplayField('secret');
        $this->setDisplayField('provider');
        $this->setDisplayField('redirect');
        $this->setDisplayField('revoked');
        $this->setDisplayField('grants');
        $this->setDisplayField('created_at');
        $this->setDisplayField('updated_at');
        $this->setDisplayField('isConfidential');
        parent::initialize($config);
    }


}
