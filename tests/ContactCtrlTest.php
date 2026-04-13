<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/ContactCtrl.php';

class ContactCtrlTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        include_once __DIR__ . '/../app/Models/Database.php';
        // Add other models if necessary, suppress errors for missing ones
        @$this->controller = new ContactCtrl();
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

    public function testSend()
    {
        ob_start();
        try {
            @$this->controller->send();
            ob_get_clean();
            $this->assertTrue(true, "send() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("send() threw an exception: " . $e->getMessage());
        }
    }

}
