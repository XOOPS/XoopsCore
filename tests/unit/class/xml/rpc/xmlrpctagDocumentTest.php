<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

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
class XoopsXmlRpcDocumentTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsXmlRpcDocumentTestInstance';
    protected $object = null;
    
    public function setUp()
    {
		$input = 'input';
		$this->object = new $this->myclass($input);
    }
    
    public function test___construct()
	{
		$instance = $this->object;
		$this->assertInstanceof($this->myclass, $instance);
	}
	
    public function test_add()
    {
		$instance = $this->object;
        
        $input = 'input';
        $object = new XoopsXmlRpcFault($input);
        $instance->add($object);
        $x = $instance->getTag();
		$this->assertSame($object, $x[0]);
    }

}
