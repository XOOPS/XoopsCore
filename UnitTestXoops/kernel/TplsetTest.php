<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TplsetTest extends MY_UnitTestCase
{
    var $myclass='XoopsTplset';

    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['tplset_id']));
        $this->assertTrue(isset($value['tplset_name']));
        $this->assertTrue(isset($value['tplset_desc']));
        $this->assertTrue(isset($value['tplset_credits']));
        $this->assertTrue(isset($value['tplset_created']));
    }

    public function test_id()
	{
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertSame(null,$value);
    }

    public function test_tplset_id()
	{
        $instance=new $this->myclass();
        $value=$instance->tplset_id();
        $this->assertSame(null,$value);
    }

    public function test_tplset_name()
	{
        $instance=new $this->myclass();
        $value=$instance->tplset_name();
        $this->assertSame(null,$value);
    }

    public function test_tplset_desc()
	{
        $instance=new $this->myclass();
        $value=$instance->tplset_desc();
        $this->assertSame(null,$value);
    }

    public function test_tplset_credits()
	{
        $instance=new $this->myclass();
        $value=$instance->tplset_credits();
        $this->assertSame(null,$value);
    }

    public function test_tplset_created()
	{
        $instance=new $this->myclass();
        $value=$instance->tplset_created();
        $this->assertSame(0,$value);
    }

}
