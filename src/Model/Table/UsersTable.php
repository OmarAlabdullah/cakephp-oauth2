<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class UsersTable extends Table
{
    /**
     * @param array $config Config
     * @return void
     * * // Setters
     * @method UsersTable setEmail(string $email)
     * @method UsersTable setPassword (string $password)
     *
     * // Getters
     * @method string getEmail
     * @method string getPassword
     *
     * @method static UsersTable builder
     * @method UsersTable build
     * @method UsersTable toBuilder
     * @
     */
    public function initialize(array $config): void
    {
        $this->setTable('users');
        $this->setPrimaryKey('identifier');
        $this->setDisplayField('password');
        $this->setDisplayField('email');
        parent::initialize($config);
    }
}
