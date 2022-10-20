<?php
declare(strict_types=1);

/**
 * @var $apiVersion string
 * @var $configVersion string
 * @var $config array
 */
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

$this->assign('title', 'CakePHP Application');
?>
<div class="container">
    <h1>Welcome to CakePHP Project Template</h1>
    <p>Version: <?= $version ?></p>
    <p>API version: <?= $apiVersion ?> (<a href="/swagger-ui/index.html?url=<?= Router::url('/v1/swagger/') ?>" title="Project Template Swagger UI" target="_blank">explore with Swagger UI</a>)</p>
    <p><a href="/info.php" title="PHP Info" target="_blank">PHP info</a></p>
    <p>Config version: <?= $configVersion ?></p>
    <table class="table service-info">
        <?php
        $connection = ConnectionManager::get('default');
        $errorMessage = "";
        try {
          $connected = $connection->connect();
        } catch (Throwable $t) {
          $connected = false;
          $errorMessage = $t->getMessage();
        }
        ?>
        <tr><td>Connected to Database</td><td><?= $connected ? 'Yes' : 'No' ?><?php if (!empty($errorMessage)) { echo " ($errorMessage)"; } ?></td></tr>
        <?php foreach($config as $key => $value): ?>
            <tr><td><?=$key?></td><td><?=$value?></td></tr>
        <?php endforeach; ?>
    </table>
</div>
