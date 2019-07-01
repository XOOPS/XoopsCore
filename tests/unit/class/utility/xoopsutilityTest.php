<?php
require_once(__DIR__ . '/../../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/class/utility/xoopsutility.php');

function myFunction($a)
{
    if (is_array($a)) {
        return array_map('myFunction', $a);
    }

    return 2 * $a;
}

class XoopsUtilityTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsUtility';

    protected function setUp()
    {
    }

    public function myFunction($a)
    {
        return -1 * $a;
    }

    public function test_recursive()
    {
        $class = $this->myClass;
        $value = 1;

        $test = $class::recursive([$this, 'myFunction'], $value);
        $this->assertSame($this->myFunction($value), $test);

        $test = $class::recursive([$this, 'toto'], $value);
        $this->assertSame($value, $test);
    }

    public function test_recursive100()
    {
        $class = $this->myClass;
        $value = 1;

        $test = $class::recursive('myFunction', $value);
        $this->assertSame(myFunction($value), $test);

        $test = $class::recursive('toto', $value);
        $this->assertSame($value, $test);
    }

    public function test_recursive200()
    {
        $class = $this->myClass;
        $value = [1, 2, 3];
        $item = [];
        foreach ($value as $k => $v) {
            $item[$k] = myFunction($v);
        }

        $test = $class::recursive('myFunction', $value);
        $this->assertSame($item, $test);
    }

    public function test_recursive300()
    {
        $class = $this->myClass;
        $value = [1, 2, 3, [10, 20, 30]];
        $item = [];
        foreach ($value as $k => $v) {
            $item[$k] = myFunction($v);
        }

        $test = $class::recursive('myFunction', $value);
        $this->assertSame($item, $test);
    }
}
