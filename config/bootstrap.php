<?php
declare(strict_types=1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use App\Application;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Database\Type\FloatType;
use Cake\Database\TypeFactory;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Security;
use Xel\Cake\Config\XelPhpConfig;
use Xel\Config\Config;
use Xel\Config\PhpConfigLoader;
use Xel\Logging\LogConfig;
use Xel\Logging\MDC;
use Xel\Logging\Processor\XelLogProcessor;
use Xel\Util\ConfigEncryption;

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
$configVersion = "1";
$configPath = "/etc/xel/xel-config-files/php-iam/$configVersion/";
try {
    $configKey = ConfigEncryption::getConfigKey();
    Configure::config('default', new XelPhpConfig($configPath, $configKey));
    Configure::load('app', 'default', false);

    //Load environment specific config
    $env = Configure::readOrFail('XEL_RUN_LEVEL');
    Configure::load("environments/config-$env", 'default', true);

    //Load Logging config, for the default logging config see vendor/xel-webservices/xel-logging/config/logging.php
    $logConfig = new LogConfig();
    $logConfig->load(Configure::readOrFail('Xel.Logging'));

    //Set the config version
    Configure::write("XelConfigVersion", $configVersion);
} catch (Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Set Application global MDC values for logging
 */
MDC::put(XelLogProcessor::ENVIRONMENT_KEY, Configure::read("XEL_RUN_LEVEL"));
MDC::put(XelLogProcessor::APPLICATION_KEY, Configure::read("Xel.Application.ShortName"));

/*
 * Determine ClientRequestId for logging
 */
$clientRequestId = $_SERVER['HTTP_X_XEL_SERVICES_CLIENTREQUESTID'] ?? trim(`uuidgen`);
MDC::put(XelLogProcessor::CLIENT_REQUEST_ID_KEY, $clientRequestId);

/*
 * When debug = false the metadata cache should last
 * for a very very long time, as we don't want
 * to refresh the cache while users are doing requests.
 */
if (!Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+1 years');
    Configure::write('Cache._cake_core_.duration', '+1 years');
}

/*
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set('UTC');

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Include the CLI bootstrap overrides.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
TypeFactory::build('time');
TypeFactory::build('date');
TypeFactory::build('datetime');
TypeFactory::build('timestamp');

Application::addTypeToLoad('decimal', FloatType::class);
