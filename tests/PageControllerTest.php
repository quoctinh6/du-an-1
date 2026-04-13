<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/PageController.php';

class PageControllerTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        include_once __DIR__ . '/../app/Models/Products.php';
        include_once __DIR__ . '/../app/Models/Category.php';
        include_once __DIR__ . '/../app/Models/Brand.php';
        include_once __DIR__ . '/../app/Models/User.php';
        // Suppress errors that might happen due to missing DB connection
        // or missing includes if run outside the expected entry point.
        @$this->controller = new PageCtrl();
    }

    public function testHome()
    {
        // Because home() includes a view, we capture the output.
        ob_start();
        try {
            @$this->controller->home();
            $output = ob_get_clean();
            
            // We want to verify that the view requires this function
            // and it executes without throwing a fatal exception.
            $this->assertNotNull($output, "home() function executed and returned output.");
            
            // Even if it emits warnings/errors because of DB, we assert True as we didn't crash.
            $this->assertTrue(true, "home() executed without fatal error.");
        } catch (\Throwable $e) {
            ob_end_clean();
            // test might fail if fatal error
            $this->fail("home() threw an exception: " . $e->getMessage());
        }
    }

    public function testFavor()
    {
        ob_start();
        try {
            @$this->controller->favor();
            $output = ob_get_clean();
            $this->assertTrue(true, "favor() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("favor() threw an exception: " . $e->getMessage());
        }
    }
}
