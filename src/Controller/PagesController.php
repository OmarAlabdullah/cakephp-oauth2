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
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Xel\Cake\Util\ServiceInfo;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {
    private string $apiVersion = 'v1';

    public function initialize(): void {
        parent::initialize();
        if (preg_match('/\/?(v\d+)\//', $this->request->getRequestTarget(), $matches)) {
            $this->apiVersion = $matches[1];
        }
        $this->Auth->allow(['home', 'swagger']);
    }

    public function home() {
        $this->set("apiVersion", $this->apiVersion);
        $this->set("version", ServiceInfo::getComposerVersion());
        $this->set("configVersion", Configure::read("XelConfigVersion"));
        $this->set("config", ServiceInfo::getConfigKeyValues('Xel', ['Xel.my-application.CryptoKey']));
    }

    public function swagger() {
        $path = func_get_args();

        $this->set("apiVersion", $this->apiVersion);
        $this->set("host", $_SERVER['HTTP_HOST'] ?? 'localhost');
        $this->response = $this->response->withHeader('Content-Type', 'application/yml');
        $this->disableAutoRender();
        $this->viewBuilder()->disableAutoLayout();

        $count = count($path);
        if (!$count) {
            $this->render("swagger/{$this->apiVersion}/index.yml");
            return;
        }

        try {
            $this->render("swagger/{$this->apiVersion}/" . implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }
}
