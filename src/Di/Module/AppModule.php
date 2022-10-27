<?php
declare(strict_types = 1);


namespace App\Di\Module;

use App\Di\Module\IAM\Auth0Provider;
use App\Di\Module\IAM\IAMServiceProvider;
use App\Services\IAM\Oauth2Service;
use App\Services\IAM\OauthService;
use Auth0\SDK\Auth0;
use Ray\Di\AbstractModule;
use Xel\Cake\Network\XelRequest;

class AppModule extends AbstractModule {

    /**
     * Configure binding
     */
    protected function configure() {
        $this->bind(XelRequest::class)->toProvider(XelRequestProvider::class);
        $this->bind(OauthService::class)->to(Oauth2Service::class);
        $this->bind(Auth0::class)->toProvider(Auth0Provider::class);
    }
}
