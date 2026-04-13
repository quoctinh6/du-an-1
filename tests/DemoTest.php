<?php
use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{
    public function testCheckSum()
    {
        $a = 20;
        $b = 20;
        $result = $a + $b;

        $this->assertEquals(40, $result);
    }
}
?>
