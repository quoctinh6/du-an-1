<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/UserCtrl.php';

class UserCtrlTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        include_once __DIR__ . '/../app/Models/Database.php';
        // Add other models if necessary, suppress errors for missing ones
        @$this->controller = new UserCtrl();
    }

    public function testLogin()
    {
        ob_start();
        try {
            @$this->controller->login();
            ob_get_clean();
            $this->assertTrue(true, "login() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("login() threw an exception: " . $e->getMessage());
        }
    }

    public function testRegister()
    {
        ob_start();
        try {
            @$this->controller->register();
            ob_get_clean();
            $this->assertTrue(true, "register() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("register() threw an exception: " . $e->getMessage());
        }
    }

    public function testInfo()
    {
        ob_start();
        try {
            @$this->controller->info();
            ob_get_clean();
            $this->assertTrue(true, "info() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("info() threw an exception: " . $e->getMessage());
        }
    }

    public function testLogout()
    {
        ob_start();
        try {
            @$this->controller->logout();
            ob_get_clean();
            $this->assertTrue(true, "logout() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("logout() threw an exception: " . $e->getMessage());
        }
    }

    public function testReset()
    {
        ob_start();
        try {
            @$this->controller->reset();
            ob_get_clean();
            $this->assertTrue(true, "reset() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("reset() threw an exception: " . $e->getMessage());
        }
    }

}
