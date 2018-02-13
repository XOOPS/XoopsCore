<?php
namespace Xoops\Core\Text\Sanitizer;

use Xoops\Core\Text\Sanitizer;

require_once __DIR__.'/../../../../../init_new.php';

class SanitizerComponentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SanitizerComponent
     */
    protected $object;

    /**
     * @var \ReflectionClass
     */
    protected $reflectedObject;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $ts = Sanitizer::getInstance();
        $this->object = $this->getMockForAbstractClass('\Xoops\Core\Text\Sanitizer\SanitizerComponent', [$ts]);
        $this->reflectedObject = new \ReflectionClass('\Xoops\Core\Text\Sanitizer\SanitizerComponent');
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
        $this->assertTrue($this->reflectedObject->isAbstract());
        $this->assertTrue($this->reflectedObject->hasMethod('getDefaultConfig'));
        $this->assertTrue($this->reflectedObject->hasProperty('ts'));
        $this->assertTrue($this->reflectedObject->hasProperty('shortcodes'));
        $this->assertTrue($this->reflectedObject->hasProperty('config'));
    }
}
