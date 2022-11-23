<?php
declare(strict_types=1);


namespace App\Di\Module;


use Cake\Routing\Router;
use Ray\Di\ProviderInterface;
use Xel\Cake\Network\XelRequest;

class XelRequestProvider implements ProviderInterface
{

    public function get()
    {
        return new XelRequest(Router::getRequest());
    }
}
