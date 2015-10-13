<?php
namespace Xoops\Core\Exception;

require_once __DIR__ . '/../../../../init_new.php';

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class NoHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InvalidHandlerSpecException
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new NoHandlerException;
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
        $this->assertInstanceOf('\Xoops\Core\Exception\NoHandlerException', $this->object);
        $this->assertInstanceOf('\DomainException', $this->object);
    }

    public function testException()
    {
        $this->setExpectedException('Xoops\Core\Exception\NoHandlerException');
        throw $this->object;
    }
}
