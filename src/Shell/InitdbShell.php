<?php
declare(strict_types=1);


namespace App\Shell;

use Xel\Cake\Clients\XelClientsShell;


class InitdbShell extends XelClientsShell {

    private $clients = [];

    public function main(...$args) {
        self::mergeClients($this->clients);
        parent::main();
    }
}
