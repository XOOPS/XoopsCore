<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BloggerApiTest extends \PHPUnit_Framework_TestCase
{
	protected $myClass = 'BloggerApi';

    public function test___construct()
	{
		$params = array(null, null, 'admin', 'adminadmin');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		$this->assertInstanceof('XoopsXmlRpcApi', $instance);
	}

    function test_newPost()
    {
		$title = '<title>Title</title>';
		$hometext = '<hometext>Hometext</hometext>';
		$moretext = '<moretext>Moretext</moretext>';
		$categories = '<categories>10</categories>';
		$text = $title . $hometext . $moretext . $categories;

		$params = array('', '', 'admin', 'adminadmin', $text);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		$instance->newPost();
		$msg = $response->render();
		if (false !== strpos($msg, '<name>faultString</name><value>Module not found'))
			$this->markTestSkipped();
		$this->markTestIncomplete();
    }

    function test_editPost()
    {
		$title = '<title>Title</title>';
		$hometext = '<hometext>Hometext</hometext>';
		$moretext = '<moretext>Moretext</moretext>';
		$categories = '<categories>10</categories>';
		$text = $title . $hometext . $moretext . $categories;

		$params = array('', '', 'admin', 'adminadmin', $text);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		$instance->editPost();
		$msg = $response->render();
		if (false !== strpos($msg, '<name>faultString</name><value>Module not found'))
			$this->markTestSkipped();
		$this->markTestIncomplete();
    }

    function test_deletePost()
    {
		$title = '<title>Title</title>';
		$hometext = '<hometext>Hometext</hometext>';
		$moretext = '<moretext>Moretext</moretext>';
		$categories = '<categories>10</categories>';
		$text = $title . $hometext . $moretext . $categories;

		$params = array('', '', 'admin', 'adminadmin', $text);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		$instance->deletePost();
		$msg = $response->render();
		if (false !== strpos($msg, '<name>faultString</name><value>Module not found'))
			$this->markTestSkipped();
		$this->markTestIncomplete();
    }

    function test_getPost()
    {
		$title = '<title>Title</title>';
		$hometext = '<hometext>Hometext</hometext>';
		$moretext = '<moretext>Moretext</moretext>';
		$categories = '<categories>10</categories>';
		$text = $title . $hometext . $moretext . $categories;

		$params = array('', '', 'admin', 'adminadmin', $text);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		$instance->getPost();
		$msg = $response->render();
		if (false !== strpos($msg, '<name>faultString</name><value>Module not found'))
			$this->markTestSkipped();
		$this->markTestIncomplete();
    }

    function test_getRecentPosts()
    {
		$title = '<title>Title</title>';
		$hometext = '<hometext>Hometext</hometext>';
		$moretext = '<moretext>Moretext</moretext>';
		$categories = '<categories>10</categories>';
		$text = $title . $hometext . $moretext . $categories;

		$params = array('', '', 'admin', 'adminadmin', $text);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		$instance->getRecentPosts();
		$msg = $response->render();
		if (false !== strpos($msg, '<name>faultString</name><value>Module not found'))
			$this->markTestSkipped();
		$this->markTestIncomplete();
    }

    function test_getUsersBlogs()
    {
		$this->markTestIncomplete();
    }

    function test_getUserInfo()
    {
		$this->markTestIncomplete();
    }

    function test_getTemplate()
    {
		$this->markTestIncomplete();
    }

    function test_setTemplate()
    {
		$this->markTestIncomplete();
    }
}
