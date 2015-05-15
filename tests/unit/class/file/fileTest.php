<?php
require_once(dirname(__FILE__).'/../../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/class/file/file.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsFileHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsFileHandler';

    public function test___construct()
	{
		$instance = new $this->myClass(__FILE__);
		$this->assertInstanceOf($this->myClass, $instance);
    }

    public function test_publicProperties()
	{
		$items = array('folder', 'name', 'info', 'handle', 'lock');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myClass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }

    public function test_create()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue(file_exists($file));
		$this->assertSame(basename($file),$instance->name);
		$this->assertSame(dirname($file),$instance->folder->path);
		$this->assertTrue(@unlink($file));
    }

    public function test_open()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, false);
		$this->assertFalse(file_exists($file));

		$result = $instance->open();
		$this->assertTrue($result);
		$this->assertTrue(file_exists($file));

		$result = $instance->open('r', true);
		$this->assertTrue($result);

		$result = $instance->open('r', true); // test force to reopen
		$this->assertTrue($result);

		$this->assertFalse(@unlink($file)); // fail to delete opened file

		unset($instance); // test destructor
		$this->assertTrue(@unlink($file)); // ok to delete closed file

		$instance = new $this->myClass($file, false);
		$this->assertFalse(file_exists($file));

		$result = $instance->open('z');
		$this->assertFalse($result);

		unset($instance);
		$this->assertTrue(@unlink($file));
    }

    public function test_read()
	{
		$file = __FILE__;
		$instance = new $this->myClass($file, false);

		$result = $instance->read();
		$result2 = file_get_contents($file);
		$this->assertSame($result, $result2);

		unset($instance);
		$instance = new $this->myClass($file, false);
		$bytes = 128;
		$result = $instance->read($bytes);
		$str = file_get_contents($file);
		$result2 = substr($str,0,$bytes);
		$this->assertSame($result, $result2);

		unset($instance);
		$instance = new $this->myClass($file, false);
		$bytes = 'notInt';
		$result1 = $instance->read($bytes);
		$value = $instance->offset(0);
		$this->assertTrue($value);
		$data = '';
		while (!feof($instance->handle)) {
			$data .= fgets($instance->handle, 4096);
		}
		$data = trim($data);
		$this->assertSame($result1, $data);
    }

    public function test_offset()
	{
		// see also test_read for test

		$file = __FILE__;
		$instance = new $this->myClass($file, false);
		$bytes = 128;
		$result = $instance->read($bytes);
		$value = $instance->offset(false);
		$this->assertTrue(is_int($value));
		$this->assertTrue($value > 0);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$instance->handle = $instance->name = null; // force open to return false
		$result = $instance->offset(0);
		$this->assertFalse($result);
    }

    public function test_prepare()
	{
		$file = __FILE__;
		$instance = new $this->myClass($file, false);
		
		$data = "line1\nline2\r\nline3\r";
		$value = $instance->prepare($data);
        if (substr(PHP_OS, 0, 3) == 'WIN') {
			$target = "line1\r\nline2\r\nline3\r\n";
		} else {
			$target = "line1\nline2\nline3\n";
		}
		$this->assertSame($target, $value);
    }

    public function test_write()
	{
		$this->markTestIncomplete();
    }

    public function test_append()
	{
		$this->markTestIncomplete();
    }

    public function test_close()
	{
		// see other test when unset $instance
		
		$file = __FILE__;
		$instance = new $this->myClass($file, false);
		$instance->handle = $instance->name = null; // force open to return false
		$result = $instance->close();
		$this->assertTrue($result);
    }

    public function test_delete()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->delete();
		$this->assertFalse($value);
		
		unset($instance);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$value = $instance->delete();
		$this->assertTrue($value);
		$this->assertFalse($instance->exists($file));
    }

    public function test_info()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$value = $instance->info();
		$this->assertTrue(is_array($value));
		$this->assertSame(dirname($file),$value['dirname']);
		$this->assertSame(basename($file),$value['basename']);
		$this->assertSame('txt',$value['extension']);
		$this->assertSame(basename($file,'.txt'),$value['filename']);
		$instance->delete();
    }

    public function test_ext()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$value = $instance->ext();
		$this->assertSame('txt',$value);
		$instance->delete();
    }

    public function test_name()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$value = $instance->name();
		$this->assertSame(basename($file,'.txt'),$value);
		$instance->delete();
		
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$this->assertFalse($instance->ext()); // ensure no extension
		$value = $instance->name();
		$this->assertSame(basename($file),$value);
		$instance->delete();
    }

    public function test_safe()
	{
		$this->markTestIncomplete();
    }

    public function test_md5()
	{
		$this->markTestIncomplete();
    }

    public function test_pwd()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$value = $instance->pwd();
		$this->assertSame($file,$value);
		$instance->delete();
    }

    public function test_exists()
	{
		// see test_delete
    }

    public function test_perms()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$perms = substr(sprintf('%o', fileperms($file)), -4);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->perms();
		$this->assertSame($perms, $value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->perms();
		$this->assertFalse($value);
    }

    public function test_size()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$length = strlen($str);
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->size();
		$this->assertSame($length, $value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->size();
		$this->assertFalse($value);
    }

    public function test_writable()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->writable();
		$this->assertTrue($value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->writable();
		$this->assertFalse($value);
    }

    public function test_executable()
	{
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->executable();
		$this->assertFalse($value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->executable();
		$this->assertFalse($value);
    }

    public function test_readable()
	{
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->readable();
		$this->assertTrue($value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->readable();
		$this->assertFalse($value);
    }

    public function test_owner()
	{
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->owner();
		$this->assertTrue(is_int($value));
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->owner();
		$this->assertFalse($value);
    }

    public function test_group()
	{
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->group();
		$this->assertTrue(is_int($value));
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->group();
		$this->assertFalse($value);
    }

    public function test_lastAccess()
	{
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$atime = fileatime($file);
		$this->assertTrue(is_int($atime));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->lastAccess();
		$this->assertSame($atime,$value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->lastAccess();
		$this->assertFalse($value);
    }

    public function test_lastChange()
	{
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		@unlink($file);
		$str = "a string for test";
		$result = file_put_contents($file, $str);
		$this->assertTrue(is_int($result));
		$atime = filemtime($file);
		$this->assertTrue(is_int($atime));
		$instance = new $this->myClass($file, false);
		$this->assertTrue($instance->exists($file));
		$value = $instance->lastChange();
		$this->assertSame($atime,$value);
		@unlink($file);
		
		unset($instance);
		$instance = new $this->myClass($file, false);
		$this->assertFalse($instance->exists($file));
		$value = $instance->lastChange();
		$this->assertFalse($value);
    }

    public function test_folder()
	{
		$file = dirname(__FILE__). DIRECTORY_SEPARATOR .'dummy.txt';
		@unlink($file);
		$instance = new $this->myClass($file, true);
		$this->assertTrue($instance->exists($file));
		$folder = $instance->folder();
		$this->assertInstanceOf('XoopsFolderHandler',$folder);
		$this->assertSame(dirname($file),$folder->path);
		@unlink($file);
    }
}
