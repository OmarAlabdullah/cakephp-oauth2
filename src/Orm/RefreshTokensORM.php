<?php

namespace App\Orm;

use App\Domain\RegisterRequest;
use App\Model\Table\RefreshTokensTable;
use App\Model\Table\UsersTable;
use Cake\ORM\Entity;
use phpDocumentor\GraphViz\Exception;

class RefreshTokensORM
{

    private RefreshTokensTable $refreshTokensTable;

    public function __construct(RefreshTokensTable $refreshTokensTable){
        $this->refreshTokensTable= $refreshTokensTable;

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
