<?php
require_once(__DIR__.'/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

class MovableTypeApiTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'MovableTypeApi';

    public function test___construct()
    {
        $params = array('p1'=>'one');
        $response = new XoopsXmlRpcResponse();
        $module = new XoopsModule();
        $x = new $this->myclass($params, $response, $module);
        $this->assertInstanceof($this->myclass, $x);
        $this->assertInstanceof('XoopsXmlRpcApi', $x);
    }

    public function test_MovableTypeApi()
    {
        $this->markTestSkipped();
    }

    public function test_getCategoryList()
    {
        $this->markTestSkipped();
    }

    public function test_getPostCategories()
    {
        $this->markTestSkipped();
    }

    public function test_setPostCategories()
    {
        $this->markTestSkipped();
    }

    public function test_supportedMethods()
    {
        $this->markTestSkipped();
    }
}
