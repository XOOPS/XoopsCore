<?php

require_once(dirname(__FILE__).'/../../init.php');

function myFunction($a)
{
	if (is_array($a)) return array_map('myFunction',$a);
	return 2*$a;
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UtilityTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsUtility';

    public function SetUp() {
    }

	public function myFunction($a)
	{
		return -1*$a;
	}

    public function test_100() {
		$value = 1;

		$test = XoopsUtility::recursive(array($this,'myFunction'), $value);
		$this->assertSame($this->myFunction($value), $test);

		$test = XoopsUtility::recursive(array($this,'toto'), $value);
		$this->assertSame($value, $test);
	}
	
	public function test_110() {
		$value = 1;
		
		$test = XoopsUtility::recursive('myFunction',$value);
		$this->assertSame(myFunction($value), $test);

		$test = XoopsUtility::recursive('toto',$value);
		$this->assertSame($value, $test);
    }

    public function test_120() {
		$value = array(1,2,3);
		$item = array();
		foreach ($value as $k => $v) {
			$item[$k] = myFunction($v);
		}

		$test = XoopsUtility::recursive('myFunction', $value);
		$this->assertSame($item, $test);
    }

    public function test_140() {
		$value = array(1,2,3,array(10,20,30));
		$item = array();
		foreach ($value as $k => $v) {
			$item[$k] = myFunction($v);
		}

		$test = XoopsUtility::recursive('myFunction',$value);
		$this->assertSame($item, $test);
    }
}
