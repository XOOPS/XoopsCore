<?php
namespace Xoops\Form;

require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-08-18 at 21:59:23.
 */
 
/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class CheckboxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Checkbox
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Checkbox('Caption', 'name');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Form\Checkbox::addOption
     */
    public function testAddOption()
    {
        // see testGetOptions
    }

    /**
     * @covers Xoops\Form\Checkbox::addOptionArray
     */
    public function testAddOptionArray()
    {
        // see testGetOptions
    }

    /**
     * @covers Xoops\Form\Checkbox::getOptions
     */
    public function testGetOptions()
    {
        $this->object->addOption('AO_value', 'AO_name');
        $this->object->addOption('AO_noname');
        
        $options = array('AOA_value' => 'AOA_name', 'AOA_noname' => '');
        $this->object->addOptionArray($options);
        
        $options = $this->object->getOptions();
        $this->assertTrue(is_array($options));
        $this->assertSame('AO_name', $options['AO_value']);
        $this->assertSame('AO_noname', $options['AO_noname']);
        $this->assertSame('AOA_name', $options['AOA_value']);
        $this->assertSame('AOA_noname', $options['AOA_noname']);
    }

    /**
     * @covers Xoops\Form\Checkbox::getInline
     */
    public function testGetInline()
    {
        $value = $this->object->getInline();
        $this->assertSame(' inline', $value);
    }

    /**
     * @covers Xoops\Form\Checkbox::render
     */
    public function testRender()
    {
        $value = $this->object->render();
        $this->assertTrue(is_string($value));
    }

    /**
     * @covers Xoops\Form\Checkbox::renderValidationJS
     */
    public function testRenderValidationJS()
    {
        $value = $this->object->renderValidationJS();
        $this->assertTrue(is_string($value));
    }
}