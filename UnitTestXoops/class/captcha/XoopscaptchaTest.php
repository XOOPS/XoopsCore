<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsCaptchaTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptcha';
    
    public function SetUp()
	{
    }
    
    public function test___construct()
	{
		$class = $this->myclass;
        $value = $class::getInstance();
        $this->assertInstanceOf($class, $value);
        $value2 = $class::getInstance();
        $this->assertSame($value2, $value);
    }

}
