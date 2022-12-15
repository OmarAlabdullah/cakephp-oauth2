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
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App;


use Xel\Cake\Error\AppExceptionRenderer;
use Xel\Cake\Middleware\AppErrorHandlerMiddleware;
use Xel\Cake\Middleware\XelBaseApplication;


/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends XelBaseApplication {

    public static function getErrorHandlerMiddleware(): AppErrorHandlerMiddleware {
        return new AppErrorHandlerMiddleware([], AppExceptionRenderer::class);
    }
}
