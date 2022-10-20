#!/usr/bin/php -q
<?php

use App\Application;
use Cake\Console\CommandRunner;
use PipingBag\Console\DICommandFactory;

require dirname(__DIR__) . '/vendor/autoload.php';
include dirname(__DIR__) . '/config/bootstrap.php';

// Build the runner with an application and root executable name.
$commandFactory = new DICommandFactory();
$runner = new CommandRunner(new Application(dirname(__DIR__) . '/config'), 'cake', $commandFactory);
exit($runner->run($argv));
