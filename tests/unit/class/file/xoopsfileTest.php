<?php
require_once(dirname(__FILE__).'/../../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/class/file/xoopsfile.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFileTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFile';

    public function test_getHandler()
	{
		$class = $this->myClass;
		$instance = $class::getHandler();
		$this->assertInstanceOf('XoopsFileHandler', $instance);
		
		unset($instance);
		$instance = $class::getHandler('folder');
		$this->assertInstanceOf('XoopsFolderHandler', $instance);
		
		unset($instance);
		$instance = $class::getHandler('NotValid');
		$this->assertFalse($instance);
		
		unset($instance);
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		$instance = $class::getHandler('file',$file);
		$this->assertInstanceOf('XoopsFileHandler', $instance);
		$this->assertFalse(file_exists($file));
		$this->assertSame(basename($file),$instance->name);
		$this->assertSame(dirname($file),$instance->folder->path);
		
		unset($instance);
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		$instance = $class::getHandler('file',$file,true);
		$this->assertInstanceOf('XoopsFileHandler', $instance);
		$this->assertTrue(file_exists($file));
		$this->assertSame(basename($file),$instance->name);
		$this->assertSame(dirname($file),$instance->folder->path);
		$this->assertTrue(@unlink($file));
		
		unset($instance);
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		$mode = '0731';
		$instance = $class::getHandler('file',$file,false,$mode);
		$this->assertInstanceOf('XoopsFileHandler', $instance);
		$this->assertFalse(file_exists($file));
		$this->assertSame(basename($file),$instance->name);
		$this->assertSame(dirname($file),$instance->folder->path);
		$this->assertSame(intval($mode,8),$instance->folder->mode);
    }
}
