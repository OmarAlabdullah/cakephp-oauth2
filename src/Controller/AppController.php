<?php
declare(strict_types=1);

namespace App\Controller;


use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Event\EventInterface;
use Exception;
use Xel\Cake\Clients\XelClientsAuth;
use Xel\Cake\Controller\XelAppController;
use Xel\Cake\Network\XelRequest;
use Ray\Di\Di\Inject;

class AppController extends XelAppController {
    protected AuthenticationComponent $Authentication;
    protected XelRequest $xelRequest;

    /**
     * @Inject
     * @param XelRequest $xelRequest
     */
    public function setXelRequest(XelRequest $xelRequest) {
        $this->xelRequest = $xelRequest;
    }

    /**
     * Initialization hook method.
     *
     * @return void
     * @throws Exception
     */

    public function initialize(): void {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        $this->reportRunLevel();

        $this->loadComponent('Flash');

        // Add this line to check authentication result and lock your site
        $this->loadComponent('Authentication.Authentication');
    }


    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        // actions public, skipping the authentication check
        $this->Authentication->addUnauthenticatedActions(['login', 'register', 'userInfo']);
    }

}
