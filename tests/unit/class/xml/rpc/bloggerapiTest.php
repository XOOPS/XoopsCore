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
		$params = array(null, 'admin', 'dummy');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
        
        $result = $instance->getUsersBlogs();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?>'
            . '<methodResponse><fault><value><struct>'
            . '<member><name>faultCode</name><value>104</value></member>'
            . "<member><name>faultString</name><value>User authentication failed\n</value></member>"
            . '</struct></value></fault></methodResponse>';
        $this->assertSame($expected, $msg);
        
        
		$params = array(null, 'admin', 'adminadmin');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
        
        $result = $instance->getUsersBlogs();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?><methodResponse><params><param><value><array><data><value><struct>'
            . '<member><name>url</name><value><string>http://localhost/projects/www/XoopsCore/htdocs/modules//</string></value></member>'
            . '<member><name>blogid</name><value><string></string></value></member>'
            . '<member><name>blogName</name><value><string>XOOPS Blog</string></value></member>'
            . '</struct></value></data></array></value></param></params></methodResponse>';
        $this->assertSame($expected, $msg);
        
    }

    function test_getUserInfo()
    {
		$params = array(null, 'admin', 'dummy');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
        
        $result = $instance->getUserInfo();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?>'
            . '<methodResponse><fault><value><struct>'
            . '<member><name>faultCode</name><value>104</value></member>'
            . "<member><name>faultString</name><value>User authentication failed\n</value></member>"
            . '</struct></value></fault></methodResponse>';
        $this->assertSame($expected, $msg);
        
		$params = array(null, 'admin', 'adminadmin');
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
        
        $result = $instance->getUserInfo();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?><methodResponse><params><param>'
            . '<value><struct><member><name>nickname</name>'
            . '<value><string>admin</string></value></member>'
            . '<member><name>userid</name><value><string>1</string></value></member>'
            . '<member><name>url</name><value><string>http://127.0.0.1/XoopsCore/htdocs</string></value></member>'
            . '<member><name>email</name><value><string>admin@admin.fr</string></value></member>'
            . '<member><name>lastname</name><value><string></string></value></member>'
            . '<member><name>firstname</name><value><string></string></value></member>'
            . '</struct></value></param></params></methodResponse>';
        $this->assertSame($expected, $msg);
    }

    function test_getTemplate()
    {
		$params = array(null, null, 'admin', 'adminadmin', null, null);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		
        $result = $instance->getTemplate();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?>'
            . '<methodResponse><fault><value><struct>'
            . '<member><name>faultCode</name><value>107</value></member>'
            . "<member><name>faultString</name><value>Method not supported\n</value></member>"
            . '</struct></value></fault></methodResponse>';
        $this->assertSame($expected, $msg);
    }

    function test_setTemplate()
    {
		$params = array(null, null, 'admin', 'adminadmin', null, null);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		
        $result = $instance->setTemplate();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?>'
            . '<methodResponse><fault><value><struct>'
            . '<member><name>faultCode</name><value>107</value></member>'
            . "<member><name>faultString</name><value>Method not supported\n</value></member>"
            . '</struct></value></fault></methodResponse>';
        $this->assertSame($expected, $msg);
        
		$params = array(null, null, 'admin', 'dummy', null, null);
		$response = new XoopsXmlRpcResponse();
		$module = new XoopsModule();
		$instance = new $this->myClass($params, $response, $module);
		
        $result = $instance->setTemplate();
        $msg = $response->render();
        $expected = '<?xml version="1.0"?>'
            . '<methodResponse><fault><value><struct>'
            . '<member><name>faultCode</name><value>104</value></member>'
            . "<member><name>faultString</name><value>User authentication failed\n</value></member>"
            . '</struct></value></fault></methodResponse>';
        $this->assertSame($expected, $msg);
    }
}
