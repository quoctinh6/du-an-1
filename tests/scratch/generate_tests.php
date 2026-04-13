<?php

$controllers = [
    'CartCtrl.php',
    'CheckoutCtrl.php',
    'ContactCtrl.php',
    'FavorCtrl.php',
    'ProductsCtrl.php',
    'ResetpwCtrl.php',
    'UserCtrl.php'
];

foreach ($controllers as $file) {
    if (!file_exists(__DIR__ . '/../../app/Controllers/' . $file)) continue;
    $content = file_get_contents(__DIR__ . '/../../app/Controllers/' . $file);
    preg_match_all('/public function\s+([a-zA-Z0-9_]+)/', $content, $matches);
    
    $methods = $matches[1];
    $className = str_replace('.php', '', $file);
    $testClassName = $className . 'Test';
    $testCode = "<?php\nuse PHPUnit\\Framework\\TestCase;\n\n";
    $testCode .= "require_once __DIR__ . '/../app/Controllers/{$file}';\n\n";
    $testCode .= "class {$testClassName} extends TestCase\n{\n";
    $testCode .= "    protected \$controller;\n\n";
    $testCode .= "    protected function setUp(): void\n    {\n";
    $testCode .= "        if (!defined('BASE_URL')) {\n            define('BASE_URL', 'http://localhost/');\n        }\n";
    $testCode .= "        include_once __DIR__ . '/../app/Models/Database.php';\n";
    $testCode .= "        // Add other models if necessary, suppress errors for missing ones\n";
    $testCode .= "        @\$this->controller = new {$className}();\n    }\n\n";
    
    foreach ($methods as $m) {
        if ($m == '__construct') continue;
        $testCode .= "    public function test" . ucfirst($m) . "()\n    {\n";
        $testCode .= "        ob_start();\n        try {\n";
        $testCode .= "            @\$this->controller->{$m}();\n";
        $testCode .= "            ob_get_clean();\n";
        $testCode .= "            \$this->assertTrue(true, \"{$m}() executed\");\n";
        $testCode .= "        } catch (\\Throwable \$e) {\n";
        $testCode .= "            ob_end_clean();\n";
        $testCode .= "            \$this->fail(\"{$m}() threw an exception: \" . \$e->getMessage());\n";
        $testCode .= "        }\n    }\n\n";
    }
    $testCode .= "}\n";
    
    file_put_contents(__DIR__ . '/../../tests/' . $testClassName . '.php', $testCode);
    echo "Generated tests/{$testClassName}.php\n";
}
