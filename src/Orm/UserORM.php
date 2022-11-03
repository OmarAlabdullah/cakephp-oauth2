<?php

namespace App\Orm;

use App\Domain\RegisterRequest;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\ORM\Entity;
use phpDocumentor\GraphViz\Exception;

class UserORM
{
    private UsersTable $usersTable;

    public function __construct(UsersTable $usersTable){
        $this->usersTable = $usersTable;

    }
    public function save(RegisterRequest $registerRequest): void {

        $email = $registerRequest->getEmail();
        $password = $registerRequest->getPassword();
        $identifier = 1233;



        $userEntity = new Entity([
            'identifier' => $identifier,
            'email' => $email,
            'password' => $password
        ]);

        try {
            $this->usersTable->saveOrFail($userEntity);
        } catch (Exception $e) {
            throw new Exception( 'something went wrong: ',$e);
        }

    }



}
