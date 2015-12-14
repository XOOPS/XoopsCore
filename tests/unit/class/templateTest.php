<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsTpl';

    /**
     * @var XoopsTpl
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new XoopsTpl();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testContracts()
    {
        $this->assertInstanceOf('\XoopsTpl', $this->object);
        $this->assertInstanceOf('\Xoops\Core\XoopsTpl', $this->object);
        $this->assertInstanceOf('\Smarty', $this->object);
    }
}
