<?php

defined('SIMPLETEST_ROOT_PATH') OR define('SIMPLETEST_ROOT_PATH',XOOPS_ROOT_PATH.'/../simpletest');

require_once SIMPLETEST_ROOT_PATH . '/autorun.php';

class MY_UnitTestCase extends UnitTestCase
{
    function __construct($label = false) {
        parent::__construct($label);
    }
	
	function assertSame($arg1, $arg2, $msg='%s')
	{
		return $this->assertIdentical($arg1, $arg2, $msg);
	}
	
	function assertEquals($arg1, $arg2)
	{
		return $this->assertEqual($arg2, $arg1);
	}
	
	function assertRegExp($arg1, $arg2)
	{
		return $this->assertPattern($arg1, $arg2);
	}

	function assertInstanceOf($arg1, $arg2)
	{
		return $this->assertIsA($arg2, $arg1);
	}
	
	function markTestSkipped($message='')
	{
		return $this->assertTrue(false);
	}

}
