<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Html\Attributes;

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class AttributesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Attributes
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Attributes;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Html\Attributes::setAttribute
     */
    public function testSetAttribute()
    {
        $instance = $this->object;
        
        $key = 'key';
        $value = 'value';
        $instance->setAttribute($key,$value);
        $result = $instance->getAttribute($key);
        $this->assertSame($value, $result);
    }

    /**
     * @covers Xoops\Html\Attributes::unsetAttribute
     */
    public function testUnsetAttribute()
    {
        $instance = $this->object;
        
        $key = 'key';
        $value = 'value';
        $instance->setAttribute($key,$value);
        $result = $instance->getAttribute($key);
        $this->assertSame($value, $result);
        
        $instance->unsetAttribute($key);
        
        $result = $instance->getAttribute($key);
        $this->assertFalse($result);
    }

    /**
     * @covers Xoops\Html\Attributes::setAttributes
     */
    public function testSetAttributes()
    {
        $instance = $this->object;
        
        $arrAttr = array('key1' =>'value1', 'key2' => 'value2', 'key3' => 'value3');
        $instance->setAttributes($arrAttr);
        
        $result = $instance->getAttribute('key1');
        $this->assertSame('value1', $result);

        $result = $instance->getAttribute('key2');
        $this->assertSame('value2', $result);
        
        $result = $instance->getAttribute('key3');
        $this->assertSame('value3', $result);
    }

    /**
     * @covers Xoops\Html\Attributes::getAttribute
     */
    public function testGetAttribute()
    {
        // see testSetAttribute
    }

    /**
     * @covers Xoops\Html\Attributes::hasAttribute
     * @todo   Implement testHasAttribute().
     */
    public function testHasAttribute()
    {
        $instance = $this->object;
        
        $key = 'key';
        $value = 'value';
        $instance->setAttribute($key,$value);
        $result = $instance->getAttribute($key);
        $this->assertSame($value, $result);
        
        $result = $instance->hasAttribute($key);
        $this->assertTrue($result);
        
        $result = $instance->hasAttribute('key_not_found');
        $this->assertFalse($result);
    }

    /**
     * @covers Xoops\Html\Attributes::addAttribute
     */
    public function testAddAttribute()
    {
        $instance = $this->object;
        
        $strAttr = 'value1 value2 value3';
        $instance->addAttribute('key',$strAttr);
        
        $result = $instance->getAttribute('key');
        $this->assertTrue(is_array($result));
        $this->assertTrue(in_array('value1', $result));
        $this->assertTrue(in_array('value2', $result));
        $this->assertTrue(in_array('value3', $result));
    }

    /**
     * @covers Xoops\Html\Attributes::renderAttributeString
     */
    public function testRenderAttributeString()
    {
        $instance = $this->object;
        
        $arrAttr = array('key1' =>'value1', 'key2' => 'value2', 'key3' => 'value3');
        $instance->setAttributes($arrAttr);
        
        $result = $instance->renderAttributeString();
        $expected = 'key1="value1" key2="value2" key3="value3" ';
        $this->assertSame($expected, $result);
    }
    
    /**
     * @covers Xoops\Html\Attributes::renderAttributeString
     */
    public function testRenderAttributeString100()
    {
        $instance = $this->object;
        
        $strAttr = 'value1 value2 value3';
        $instance->addAttribute('key',$strAttr);
        
        $result = $instance->renderAttributeString();
        $expected = 'key="value1 value2 value3" ';
        $this->assertSame($expected, $result);
    }
}
