<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RanksTest extends \PHPUnit_Framework_TestCase
{
    var $myclass='XoopsRanks';

    public function setUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['rank_id']));
        $this->assertTrue(isset($value['rank_title']));
        $this->assertTrue(isset($value['rank_min']));
        $this->assertTrue(isset($value['rank_max']));
        $this->assertTrue(isset($value['rank_special']));
        $this->assertTrue(isset($value['rank_image']));
    }
}
