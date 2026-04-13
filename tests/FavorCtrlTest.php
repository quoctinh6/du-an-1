<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/FavorCtrl.php';

class FavorCtrlTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        include_once __DIR__ . '/../app/Models/Database.php';
        // Add other models if necessary, suppress errors for missing ones
        @$this->controller = new FavorCtrl();
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

    public function testAdd()
    {
        ob_start();
        try {
            @$this->controller->add();
            ob_get_clean();
            $this->assertTrue(true, "add() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("add() threw an exception: " . $e->getMessage());
        }
    }

    public function testRemove()
    {
        ob_start();
        try {
            @$this->controller->remove();
            ob_get_clean();
            $this->assertTrue(true, "remove() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("remove() threw an exception: " . $e->getMessage());
        }
    }

}
