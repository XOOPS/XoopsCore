<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CachemodelTest extends MY_UnitTestCase
{
    var $myclass='XoopsCachemodelObject';

    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['cache_key']));
        $this->assertTrue(isset($value['cache_data']));
        $this->assertTrue(isset($value['cache_expires']));
    }

}
