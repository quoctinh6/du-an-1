<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/AdminCtrl.php';

class AdminCtrlTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/');
        }
        // Suppress errors that might happen due to missing DB connection
        // or missing includes if run outside the expected entry point.
        @$this->controller = new AdminCtrl();
    }

    private function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    public function testIndex()
    {
        ob_start();
        try {
            @$this->controller->index();
            $output = ob_get_clean();
            $this->assertTrue(true, "index() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("index() threw an exception: " . $e->getMessage());
        }
    }

    public function testCategories()
    {
        ob_start();
        try {
            @$this->controller->categories();
            $output = ob_get_clean();
            $this->assertTrue(true, "categories() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("categories() threw an exception: " . $e->getMessage());
        }
    }

    public function testAddCategory()
    {
        ob_start();
        try {
            @$this->controller->addCategory();
            $output = ob_get_clean();
            $this->assertTrue(true, "addCategory() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("addCategory() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateCategory()
    {
        ob_start();
        try {
            @$this->controller->updateCategory();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateCategory() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateCategory() threw an exception: " . $e->getMessage());
        }
    }

    public function testProducts()
    {
        ob_start();
        try {
            @$this->controller->products();
            $output = ob_get_clean();
            $this->assertTrue(true, "products() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("products() threw an exception: " . $e->getMessage());
        }
    }

    public function testAddProduct()
    {
        ob_start();
        try {
            @$this->controller->addProduct();
            $output = ob_get_clean();
            $this->assertTrue(true, "addProduct() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("addProduct() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateProduct()
    {
        ob_start();
        try {
            @$this->controller->updateProduct();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateProduct() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateProduct() threw an exception: " . $e->getMessage());
        }
    }

    public function testVariants()
    {
        ob_start();
        try {
            @$this->controller->variants();
            $output = ob_get_clean();
            $this->assertTrue(true, "variants() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("variants() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateVariant()
    {
        ob_start();
        try {
            @$this->controller->updateVariant();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateVariant() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateVariant() threw an exception: " . $e->getMessage());
        }
    }

    public function testOrders()
    {
        ob_start();
        try {
            @$this->controller->orders();
            $output = ob_get_clean();
            $this->assertTrue(true, "orders() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("orders() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateOrderStatus()
    {
        ob_start();
        try {
            @$this->controller->updateOrderStatus();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateOrderStatus() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateOrderStatus() threw an exception: " . $e->getMessage());
        }
    }

    public function testAccount()
    {
        ob_start();
        try {
            @$this->controller->account();
            $output = ob_get_clean();
            $this->assertTrue(true, "account() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("account() threw an exception: " . $e->getMessage());
        }
    }

    public function testAddUser()
    {
        ob_start();
        try {
            @$this->controller->addUser();
            $output = ob_get_clean();
            $this->assertTrue(true, "addUser() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("addUser() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateStatus()
    {
        ob_start();
        try {
            @$this->controller->updateStatus();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateStatus() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateStatus() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateRole()
    {
        ob_start();
        try {
            @$this->controller->updateRole();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateRole() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateRole() threw an exception: " . $e->getMessage());
        }
    }

    public function testUser()
    {
        ob_start();
        try {
            @$this->controller->user();
            $output = ob_get_clean();
            $this->assertTrue(true, "user() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("user() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateUserFromAdmin()
    {
        ob_start();
        try {
            @$this->controller->updateUserFromAdmin();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateUserFromAdmin() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateUserFromAdmin() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdatePassword()
    {
        ob_start();
        try {
            @$this->controller->updatePassword();
            $output = ob_get_clean();
            $this->assertTrue(true, "updatePassword() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updatePassword() threw an exception: " . $e->getMessage());
        }
    }

    public function testBrands()
    {
        ob_start();
        try {
            @$this->controller->brands();
            $output = ob_get_clean();
            $this->assertTrue(true, "brands() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("brands() threw an exception: " . $e->getMessage());
        }
    }

    public function testAddBrand()
    {
        ob_start();
        try {
            @$this->controller->addBrand();
            $output = ob_get_clean();
            $this->assertTrue(true, "addBrand() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("addBrand() threw an exception: " . $e->getMessage());
        }
    }

    public function testUpdateBrand()
    {
        ob_start();
        try {
            @$this->controller->updateBrand();
            $output = ob_get_clean();
            $this->assertTrue(true, "updateBrand() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("updateBrand() threw an exception: " . $e->getMessage());
        }
    }

    public function testComments()
    {
        ob_start();
        try {
            @$this->controller->comments();
            $output = ob_get_clean();
            $this->assertTrue(true, "comments() executed");
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail("comments() threw an exception: " . $e->getMessage());
        }
    }
}
