<?php
declare(strict_types=1);


namespace App\Controller;


use PHPUnit\Framework\TestCase;

class PagesControllerTest extends TestCase {

    /** @var PagesController */
    private $controller;

    protected function setUp(): void {
        $this->controller = new PagesController();
    }

    public function testHome() {
        $this->controller->home();
        $viewVars = $this->controller->viewBuilder()->getVars();
        self::assertThat($viewVars['apiVersion'], self::equalTo("v1"));
        self::assertNotEmpty($viewVars['version']);
    }
}
