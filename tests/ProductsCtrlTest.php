<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/ProductsCtrl.php';

class ProductsCtrlTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        include_once __DIR__ . '/../app/Models/Database.php';
        // Add other models if necessary, suppress errors for missing ones
        @$this->controller = new ProductsCtrl();
    }

    public function testCategory()
    {
        ob_start();
        try {
            @$this->controller->category();
            ob_get_clean();
            $this->assertTrue(true, "category() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("category() threw an exception: " . $e->getMessage());
        }
    }

    public function testDetail()
    {
        ob_start();
        try {
            @$this->controller->detail();
            ob_get_clean();
            $this->assertTrue(true, "detail() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("detail() threw an exception: " . $e->getMessage());
        }
    }

    public function testAddComment()
    {
        ob_start();
        try {
            @$this->controller->addComment();
            ob_get_clean();
            $this->assertTrue(true, "addComment() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("addComment() threw an exception: " . $e->getMessage());
        }
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

}
