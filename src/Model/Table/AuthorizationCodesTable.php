<?php

namespace App\Model\Table;

use Cake\ORM\Table;


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
        parent::initialize($config);
    }
}
