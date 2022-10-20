<?php
declare(strict_types=1);

require_once("vendor/autoload.php");
require_once("config/bootstrap.php");

$projectDriverPath = dirname(APP) . "/src"; // Optionally add the correct driver path whenever it could not be located automatically
\Xel\Cake\Util\XelSwaggerGenerator::generate(dirname(APP), $projectDriverPath, false);
