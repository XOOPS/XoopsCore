<?php
require_once(dirname(__FILE__).'/../../init.php');
 
class XoopsCaptchaTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsCaptcha';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $value = XoopsCaptcha::getInstance();
        $this->assertInstanceOf($this->myclass, $value);
        $value2 = XoopsCaptcha::getInstance();
        $this->assertSame($value2, $value);
    }

}
