<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/CheckoutCtrl.php';

class CheckoutCtrlTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        include_once __DIR__ . '/../app/Models/Database.php';
        // Add other models if necessary, suppress errors for missing ones
        @$this->controller = new CheckoutCtrl();
    }

    public function testIndex()
    {
        ob_start();
        try {
            @$this->controller->index();
            ob_get_clean();
            $this->assertTrue(true, "index() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("index() threw an exception: " . $e->getMessage());
        }
    }

    public function testProcess()
    {
        ob_start();
        try {
            @$this->controller->process();
            ob_get_clean();
            $this->assertTrue(true, "process() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("process() threw an exception: " . $e->getMessage());
        }
    }

    public function testSuccess()
    {
        ob_start();
        try {
            @$this->controller->success();
            ob_get_clean();
            $this->assertTrue(true, "success() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("success() threw an exception: " . $e->getMessage());
        }
    }

}
