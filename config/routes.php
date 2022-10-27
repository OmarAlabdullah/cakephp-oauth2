<?php
declare(strict_types=1);

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
return static function (RouteBuilder $routes) {

    Router::defaultRouteClass(DashedRoute::class);


    $routes->scope('/', function (RouteBuilder $routes) {
        /**
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, src/Template/Pages/home.php)...
         */
        $routes->connect('/', ['controller' => 'Pages', 'action' => 'home']);

        $routes->scope('/v1/', function (RouteBuilder $routes) {
            // Setup default route for V1 'home':
            $routes->connect(
                '/',
                ['controller' => 'Pages', 'action' => 'home', '_method' => 'GET']
            );

            // Setup route for V1 swagger files:
            $routes->connect(
                '/swagger/*',
                ['controller' => 'Pages', 'action' => 'swagger', '_method' => 'GET']
            );

            // Login routes
            $routes->connect('/login',
                ['controller' => 'Oauth', 'action' => 'login', '_method' => 'POST']
            );

            // register routes
            $routes->connect('/register',
                ['controller' => 'Oauth', 'action' => 'register', '_method' => 'POST']);

            // change password routes
            $routes->connect('/change-password',
                ['controller' => 'Oauth', 'action' => 'changePassword', '_method' => 'POST']);

            // logout routes
            $routes->connect('/logout',
                ['controller' => 'Oauth', 'action' => 'logout', '_method' => 'POST']);

            // sso routes
            $routes->connect('/google',
                ['controller' => 'Oauth', 'action' => 'sso', '_method' => 'POST']);


        });

        $routes->setExtensions(['json']);

        /**
         * Connect catchall routes for all controllers.
         *
         * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
         *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
         *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
         *
         * Any route class can be used with this method, such as:
         * - DashedRoute
         * - InflectedRoute
         * - Route
         * - Or your own route class
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $routes->fallbacks(DashedRoute::class);
    });
};
