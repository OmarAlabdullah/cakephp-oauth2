<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @method Query findByUsername(string $userName)
 * @method Query findByIdOrUsername($idOrUserName1, $idOrUserName2)
 */
class ClientsTable extends Table
{
    public function initialize(array $config): void {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->notEmptyString('username', 'A username is required')
            ->notEmptyString('password', 'A password is required')
            ->notEmptyString('role', 'A role is required')
            ->notEmptyString('email', 'An email address is required')
            ->notEmptyString('active', 'An activation status is required')
            ->notEmptyString('role', 'inList', [
                'rule' => ['inList', ['admin', 'client']],
                'message' => 'Please enter a valid role'
            ]);
    }

    public function findAuth(Query $query) {
        $query->select(['id', 'username', 'password', 'role', 'email'])->where(['Clients.active' => 1]);
        return $query;
    }
}
