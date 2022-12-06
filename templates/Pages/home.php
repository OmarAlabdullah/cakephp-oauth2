<?php
declare(strict_types=1);

/**
 * @var $apiVersion string
 * @var $configVersion string
 * @var $config array
 */

use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

$this->assign('title', 'Xel-SSO');
?>
<div class="container">
    <h1>Welcome to Xel SSO</h1>

    <h2>Login</h2>
    <button><a href="/login" title="login" target="_blank">Login</a></button>

    <h2>Register</h2>
    <button><a href="/register" title="login" target="_blank">Register</a></button>

</div>
