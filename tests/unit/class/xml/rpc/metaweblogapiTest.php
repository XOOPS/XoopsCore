<?php
require_once(__DIR__.'/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

class MetaWeblogApiTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'MetaWeblogApi';

    public function test___construct()
    {
        $params = array('p1'=>'one');
        $response = new XoopsXmlRpcResponse();
        $module = new XoopsModule();
        $x = new $this->myclass($params, $response, $module);
        $this->assertInstanceof($this->myclass, $x);
        $this->assertInstanceof('XoopsXmlRpcApi', $x);
    }

    public function test_MetaWeblogApi()
    {
        $this->markTestSkipped();
    }

    public function test_newPost()
    {
        $this->markTestSkipped();
    }

    public function test_editPost()
    {
        $this->markTestSkipped();
    }

    public function test_getPost()
    {
        $this->markTestSkipped();
    }

    public function test_getRecentPosts()
    {
        $this->markTestSkipped();
    }

    public function test_getCategories()
    {
        $this->markTestSkipped();
    }
}
