<?php

namespace App\Orm;

use App\Domain\EmailRequest;
use App\Domain\RegisterRequest;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Xel\Common\ErrorCodes;
use Xel\Common\Exception\ServiceException;

class UserORM implements UserRepositoryInterface
{
    private UsersTable $usersTable;

    public function __construct(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;

    }

    /**
     * @throws ServiceException
     */
    public function saveUser(RegisterRequest $registerRequest): void
    {

        $email = $registerRequest->getEmail();
        $password = $registerRequest->getPassword();


        $userEntity = new Entity([
            'email' => $email,
            'password' => $password
        ]);

        try {
            $this->usersTable->saveOrFail($userEntity);
        } catch (\Exception $t) {
            throw new ConflictException("Cannot save user, Error: $t", ErrorCodes::SERVER_ERROR, $t);
        }
    }


    /**
     * @throws ServiceException
     */
    public function getUser(EmailRequest $emailRequest)
    {
        $email = $emailRequest->getEmail();
        try {
            return $this->usersTable->find()
                ->where(["email" => $email])
                ->firstOrFail();
        } catch (\Throwable $t) {
            throw new NotFoundException("user not found");
        }

    }

    /**
     * @throws ServiceException
     */
    public function deleteUser(RegisterRequest $registerRequest): void
    {

        $email = $registerRequest->getEmail();
        $password = $registerRequest->getPassword();

        $userEntity = new Entity([
            'email' => $email,
            'password' => $password
        ]);

        try {
            $this->usersTable->delete($userEntity);
        } catch (\Exception $t) {
            throw new ServiceException("User not found, Error: $t", ErrorCodes::SERVER_ERROR, $t);
        }
    }


    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        {

            try {
                return $this->usersTable->find()
                    ->where(["email" => $username])
                    ->firstOrFail();
            } catch (\Throwable $t) {
                throw new NotFoundException("user not found");
            }

        }
    }
}
