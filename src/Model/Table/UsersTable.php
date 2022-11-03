<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class UsersTable extends Table
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('users');
        $this->setPrimaryKey('id');
        $this->setDisplayField('password');
        $this->setDisplayField('email');
        parent::initialize($config);
    }
}
