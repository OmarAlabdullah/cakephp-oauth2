<?php

namespace App\Di\Module\IAM;

use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Ray\Di\ProviderInterface;

class Auth0Provider implements ProviderInterface {

    public function get(): Auth0 {
        $configuration = new SdkConfiguration([
            'domain' => 'dev-7ys-j0kk.us.auth0.com',
            'clientId' => 'IDfC5gcuoD5QAybm7u6zVJPcXaq1KgTa',
            'clientSecret' => 't0JFPKsGiWt3V3Izb4YGIxwOZycxFKqwVFHmJcMItFq8ZuGm3zjR47hX2pyOILkB',
            'cookieSecret' => '_9O8vaN9jy2aPlma8PA38MCAYY0XCxyCI6DnkDdmCMJQoTwffqsId7rVSJ9ny6hj',
            'audience' => ['https://php-iam.xel-localservices.nl'],
            'organization' => ['test'],
        ]);
        return new Auth0($configuration);
    }
}
