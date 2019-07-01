<?php
require_once __DIR__ . '/../../init_new.php';

class Xoops_UtilsTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = '\Xoops\Utils';
    protected $save_SERVER = null;
    protected $save_ENV = null;

    protected function setUp()
    {
        $this->save_SERVER = $_SERVER;
        $this->save_ENV = $_ENV;
        $this->markTestSkipped('side effects');
        if (!function_exists('ini_get') || '1' === ini_get('safe_mode')) {
            $this->markTestSkipped('safe mode is on');
        }
    }

    protected function tearDown()
    {
        $_SERVER = $this->save_SERVER;
        $_ENV = $this->save_ENV;
    }

    public function test_dumpVar()
    {
        $class = $this->myClass;
        $var = [1 => 'test'];
        ob_start();
        $x = $class::dumpVar($var, false, false);
        $buf = ob_get_clean();
        $this->assertInternalType('string', $x);
        $this->assertEmpty($buf);

        ob_start();
        $x = $class::dumpVar($var, true, false);
        $buf = ob_get_clean();
        $this->assertTrue(!empty($x));
        $this->assertInternalType('string', $x);
        $this->assertTrue(!empty($buf));
        $this->assertInternalType('string', $buf);
    }

    public function test_dumpFile()
    {
        $class = $this->myClass;
        $file = __FILE__;
        ob_start();
        $x = $class::dumpFile($file, false, false);
        $buf = ob_get_clean();
        $this->assertInternalType('string', $x);
        $this->assertEmpty($buf);

        ob_start();
        $x = $class::dumpFile($file, true, false);
        $buf = ob_get_clean();
        $this->assertTrue(!empty($x));
        $this->assertInternalType('string', $x);
        $this->assertTrue(!empty($buf));
        $this->assertInternalType('string', $buf);
    }

    public function test_arrayRecursiveDiff()
    {
        $class = $this->myClass;

        $array1 = ['a' => 'green', 'red', 'blue', 'red'];
        $array2 = ['b' => 'green', 'yellow', 'red'];

        $x = $class::arrayRecursiveDiff($array1, $array1);
        $this->assertEmpty($x);
        $this->assertInternalType('array', $x);

        $x = $class::arrayRecursiveDiff($array1, $array2);
        $this->assertInternalType('array', $x);
        $this->assertTrue('green' == $x['a']);
        $this->assertTrue('red' == $x[0]);
        $this->assertTrue('blue' == $x[1]);
        $this->assertTrue('red' == $x[2]);
    }

    public function test_arrayRecursiveDiff100()
    {
        $class = $this->myClass;
        $array1 = ['a' => 'green', 'red', ['a' => 'green', 'red', 'blue']];
        $array2 = ['b' => 'green', 'red', ['b' => 'green', 'blue', 'red']];

        $x = $class::arrayRecursiveDiff($array1, $array1);
        $this->assertEmpty($x);
        $this->assertInternalType('array', $x);

        $x = $class::arrayRecursiveDiff($array1, $array2);
        $this->assertInternalType('array', $x);
        $this->assertTrue('green' == $x['a']);
        $this->assertTrue('green' == $x[1]['a']);
        $this->assertTrue('red' == $x[1][0]);
        $this->assertTrue('blue' == $x[1][1]);
    }

    public function test_arrayRecursiveDiff120()
    {
        $class = $this->myClass;
        $array1 = ['a' => 'green', 'red', 'array' => ['a' => 'green', 'red', 'blue']];
        $array2 = ['b' => 'green', 'red', 'array' => 'blue'];

        $x = $class::arrayRecursiveDiff($array1, $array1);
        $this->assertEmpty($x);
        $this->assertInternalType('array', $x);

        $x = $class::arrayRecursiveDiff($array1, $array2);
        $this->assertInternalType('array', $x);
        $this->assertTrue('green' == $x['a']);
        $this->assertTrue('green' == $x['array']['a']);
        $this->assertTrue('red' == $x['array'][0]);
        $this->assertTrue('blue' == $x['array'][1]);
    }

    public function test_arrayRecursiveDiff140()
    {
        $class = $this->myClass;
        $array1 = ['a' => 'green', 'red', 'array' => ['a' => 'green', 'red', 'blue']];
        $array2 = ['b' => 'green', 'red', 'array' => ['b' => 'green']];

        $x = $class::arrayRecursiveDiff($array1, $array1);
        $this->assertEmpty($x);
        $this->assertInternalType('array', $x);

        $x = $class::arrayRecursiveDiff($array1, $array2);
        $this->assertInternalType('array', $x);
        $this->assertTrue('green' == $x['a']);
        $this->assertTrue('green' == $x['array']['a']);
        $this->assertTrue('red' == $x['array'][0]);
        $this->assertTrue('blue' == $x['array'][1]);
    }

    public function test_arrayRecursiveDiff160()
    {
        $class = $this->myClass;
        $array1 = ['a' => 'green', 'red', 'array' => ['a' => 'green', 'red', 'blue']];
        $array2 = [];

        $x = $class::arrayRecursiveDiff($array1, $array2);
        $this->assertInternalType('array', $x);
        $this->assertTrue($x == $array1);

        $x = $class::arrayRecursiveDiff($array2, $array1);
        $this->assertInternalType('array', $x);
        $this->assertEmpty($x);
    }

    public function test_arrayRecursiveMerge()
    {
        $class = $this->myClass;
        $array1 = ['a' => 'green', 'red', 'array' => ['a' => 'green', 'red', 'blue']];
        $array2 = ['b' => 'green', 'red', 'array' => ['a' => 'green', 'yellow']];

        $x = $class::arrayRecursiveMerge($array1, $array2);
        $this->assertInternalType('array', $x);
        $this->assertTrue('green' == $x['a']);
        $this->assertTrue('green' == $x['array']['a']);
        $this->assertTrue('red' == $x['array'][0]);
        $this->assertTrue('blue' == $x['array'][1]);
        $this->assertTrue('green' == $x['b']);
        $this->assertTrue('yellow' == $x['array'][2]);
    }

    public function test_arrayRecursiveMerge100()
    {
        $class = $this->myClass;
        $array1 = ['a' => 'green', 'red', 'array' => ['a' => 'green', 'red', 'blue']];

        $x = $class::arrayRecursiveMerge($array1, $array1);
        $this->assertInternalType('array', $x);
        $this->assertTrue($x == $array1);
    }
}
