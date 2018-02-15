<?php
namespace Xmf\Module\Helper;

require_once(__DIR__.'/../../../../init_new.php');

class AbstractHelperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var AbstractHelper
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->object = new \Xmf\Module\Helper\AbstractHelper;
        $this->object = $this->getMockForAbstractClass('Xmf\Module\Helper\AbstractHelper');
        //$this->object->expects($this->any())
        //    ->method('getDefaultParams')
        //    ->will($this->returnValue(array()));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testSetDebug()
    {
        $this->assertTrue(method_exists($this->object, 'setDebug'));
        $this->object->setDebug(true);
        $this->assertAttributeEquals(true, 'debug', $this->object);
        $this->object->setDebug(false);
        $this->assertAttributeEquals(false, 'debug', $this->object);
    }

    public function testAddLog()
    {
        $this->assertTrue(method_exists($this->object, 'addLog'));
        $this->object->addLog('message to send to bitbucket');
    }
}
