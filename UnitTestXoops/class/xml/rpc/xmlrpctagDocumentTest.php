<?php
require_once(dirname(__FILE__).'/../../../init.php');

class XoopsXmlRpcDocumentTestInstance extends XoopsXmlRpcDocument
{
	function render() {}
    function getTag()
    {
        return $this->_tags;
    }
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsXmlRpcDocumentTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsXmlRpcDocumentTestInstance';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceof($this->myclass, $instance);
	}
	
    public function test_add()
    {
		$instance = new $this->myclass();
        
        $object = new XoopsXmlRpcFault();
        $instance->add($object);
        $x = $instance->getTag();
		$this->assertSame($object, $x[0]);
    }

}
