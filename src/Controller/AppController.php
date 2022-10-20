<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Response;
use Xel\Cake\Clients\XelClientsAuth;
use Xel\Cake\Controller\XelAppController;
use Xel\Cake\Network\XelRequest;
use Ray\Di\Di\Inject;

class AppController extends XelAppController {
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
     */
    public function initialize(): void {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        XelClientsAuth::loadAuthComponent($this);

        $this->reportRunLevel();
    }
}
