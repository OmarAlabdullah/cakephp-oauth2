<?php
declare(strict_types = 1);


namespace App\Di\Module;


use App\Model\Table\AccessTokensTable;
use App\Model\Table\AuthorizationCodesTable;
use App\Model\Table\ClientsTable;
use App\Model\Table\UsersTable;
use App\Orm\AccessTokenORM;
use App\Orm\AuthorizationCodeORM;
use App\Orm\ClientORM;
use App\Orm\RefreshTokenORM;
use App\Orm\UserORM;
use App\Services\oauth\Oauth2Service;
use App\Services\oauth\OauthService;
use App\Services\oauth\TokenService;
use App\Services\oauth\TokenServiceInterface;
use Cake\ORM\TableRegistry;
use App\Model\Table\RefreshTokensTable;
use Ray\Di\AbstractModule;
use Xel\Cake\Network\XelRequest;

class AppModule extends AbstractModule {

    /**
     * Configure binding
     */
    protected function configure() {
        $this->bind(XelRequest::class)->toProvider(XelRequestProvider::class);
        $this->bind(OauthService::class)->to(Oauth2Service::class);
        $this->bind(TokenServiceInterface::class)->to(TokenService::class);
        $this->bind(UserOrm::class);
        $this->bind(AccessTokenORM::class);
        $this->bind(RefreshTokenORM::class);
        $this->bind(ClientORM::class);
        $this->bind(AuthorizationCodeORM::class);

        $this->bind(UsersTable::class)->toInstance(TableRegistry::getTableLocator()->get('Users'));
        $this->bind(AccessTokensTable::class)->toInstance(TableRegistry::getTableLocator()->get('AccessTokens'));
        $this->bind(AuthorizationCodesTable::class)->toInstance(TableRegistry::getTableLocator()->get('AuthorizationCodes'));
        $this->bind(RefreshTokensTable::class)->toInstance(TableRegistry::getTableLocator()->get('RefreshTokens'));
        $this->bind(ClientsTable::class)->toInstance(TableRegistry::getTableLocator()->get('Clients'));


    }
}
