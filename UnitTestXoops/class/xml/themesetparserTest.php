<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsThemeSetParserTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsThemeSetParser';

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
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('SaxParser', $instance);
		
		$this->assertSame($input, $instance->input);
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
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
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
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
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
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
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
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
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
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		
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