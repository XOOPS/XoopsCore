<?php
require_once(dirname(__FILE__).'/../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsThemeSetParserTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsThemeSetParser';
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new $this->myclass($input);
    }

    public function test___publicProperties()
	{
		$items = array('tempArr', 'themeSetData', 'imagesData', 'templatesData');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myclass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
    public function test___construct()
    {
        $instance = $this->object;
		$this->assertInstanceOf('SaxParser', $instance);
		
		$tagHandlers = $instance->tagHandlers;
		$this->assertTrue(is_array($tagHandlers));
		if (is_array($tagHandlers)) {
			$this->assertSame(12, count($tagHandlers));
		}
    }

    public function test_setThemeSetData()
    {
		// see test_getThemeSetData()
    }

    public function test_getThemeSetData()
    {
        $instance = $this->object;
		
		$name = 'name';
		$value = 'value';
		$instance->setThemeSetData($name, $value);
		$x = $instance->getThemeSetData('bidon');
		$this->assertFalse($x);
		
		$x = $instance->getThemeSetData($name);
		$this->assertSame($value, $x);
		
		$x = $instance->getThemeSetData();
		$this->assertTrue(is_array($x) AND ($x[$name] == $value));
    }

    public function test_setImagesData()
    {
		// test_getImagesData()
    }

    public function test_getImagesData()
    {
        $instance = $this->object;
		
		$arr = array(1=>'1', 2=>'2');
		$instance->setImagesData($arr);
		$x = $instance->getImagesData();
		$this->assertTrue(is_array($x) AND (count($x)==1));
		
		$arr = array(1=>'1', 2=>'2');
		$instance->setImagesData($arr);
		$x = $instance->getImagesData();
		$this->assertTrue(is_array($x) AND (count($x)==2));
    }

    public function test_setTemplatesData()
    {
		// test_getTemplatesData
    }

    public function test_getTemplatesData()
    {
        $instance = $this->object;
		
		$arr = array(1=>'1', 2=>'2');
		$instance->setTemplatesData($arr);
		$x = $instance->getTemplatesData();
		$this->assertTrue(is_array($x) AND (count($x)==1));
		
		$arr = array(1=>'1', 2=>'2');
		$instance->setTemplatesData($arr);
		$x = $instance->getTemplatesData();
		$this->assertTrue(is_array($x) AND (count($x)==2));
    }

    public function test_setTempArr()
    {
		// test_getTempArr()
    }

    public function test_getTempArr()
    {
        $instance = $this->object;
		
		$name = 'name';
		$value = 'value';
		$delim = ';';
		$instance->setTempArr($name, $value);
		$x = $instance->getTempArr($name);
		$this->assertSame($value, $x);
		
		$x = $instance->getTempArr('bidon');
		$this->assertSame(false, $x);
		
		$x = $instance->getTempArr();
		$this->assertTrue(is_array($x) AND ($x[$name] == $value));
		
		$instance->setTempArr($name, $value, $delim);
		$x = $instance->getTempArr($name);
		$this->assertSame($value.$delim.$value, $x);
    }

    public function test_resetTempArr()
    {
        $instance = $this->object;
		
		$name = 'name';
		$value = 'value';
		$delim = ';';
		$instance->setTempArr($name, $value);
		$x = $instance->getTempArr($name);
		$this->assertSame($value, $x);
		
		$instance->resetTempArr();
		
		$x = $instance->getTempArr($name);
		$this->assertSame(false, $x);
		
    }
}