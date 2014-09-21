<?php
namespace Xmf;

require_once(dirname(dirname(__DIR__)) . '/init_mini.php');

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-05-22 at 19:56:36.
 */

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class LoaderTest extends \MY_UnitTestCase
{
    /**
     * @var Loader
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Loader;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xmf\Loader::loadFile
     */
    public function testLoadFile()
    {
        $x = Loader::loadFile('DebugTest.php');
        $this->assertSame(true, $x);

        $x = Loader::loadFile('FilterInputTest.php', false);
        $this->assertSame(true, $x);

        $x = Loader::loadFile('thisfiledoesntexists');
        $this->assertSame(false, $x);
    }

    /**
     * @covers Xmf\Loader::loadClass
     */
    public function testLoadClass()
    {
        $x = Loader::loadClass(__class__);
        $this->assertSame(true, $x);

        $x = Loader::loadClass('Xmf\DebugTest');
        $this->assertSame(true, $x);

        $x = Loader::loadClass('thisClassdoesntexists');
        $this->assertSame(false, $x);
    }
}
