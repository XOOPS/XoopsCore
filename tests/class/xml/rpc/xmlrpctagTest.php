<?php
require_once(dirname(__FILE__).'/../../../init.php');

require_once(XOOPS_ROOT_PATH.'/class/xml/rpc/xmlrpcparser.php');

class XoopsXmlRpcTagTestInstance extends XoopsXmlRpcTag
{
	function render() {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcTagTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcTagTestInstance';

    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceof($this->myclass, $instance);
	}

    public function test_encode()
    {
		$instance = new $this->myclass();
        $text = '&TESTtest71; & #||TESTtest71||# < > ';
        $x = $instance->encode($text);
        $result = '#||TESTtest71||# &amp; &TESTtest71; &lt; &gt; ';
		$this->assertSame($result, $x);
    }

    public function test_setFault()
    {
		$instance = new $this->myclass();

        $instance->setFault(true);
        $x = $instance->isFault();
		$this->assertSame(true, $x);

        $instance->setFault(false);
        $x = $instance->isFault();
		$this->assertSame(false, $x);
    }

    public function test_isFault()
    {
		// see test_setFault
    }

}
