<?php
require_once(dirname(__FILE__).'/../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsTest extends MY_UnitTestCase
{

    public function test_getInstance100()
	{
        $instance = Xoops::getInstance();
        $this->assertInstanceOf('Xoops', $instance);

        $instance2=Xoops::getInstance();
        $this->assertSame($instance, $instance2);

        // First initialization in first test
        if (!class_exists('Xoops_Locale', false)) {
            $value = $instance->loadLocale();
            $this->assertSame(true, $value);
        }

        $this->assertSame(array(XOOPS_PATH, XOOPS_URL . '/browse.php'), $instance->paths['XOOPS']);
        $this->assertSame(array(XOOPS_ROOT_PATH, XOOPS_URL), $instance->paths['www']);
        $this->assertSame(array(XOOPS_VAR_PATH, null), $instance->paths['var']);
        $this->assertSame(array(XOOPS_PATH, XOOPS_URL . '/browse.php'), $instance->paths['lib']);
        $this->assertSame(array(XOOPS_ROOT_PATH . '/modules', XOOPS_URL . '/modules'), $instance->paths['modules']);
        $this->assertSame(array(XOOPS_ROOT_PATH . '/themes', XOOPS_URL . '/themes'), $instance->paths['themes']);
        $this->assertSame(array(XOOPS_ROOT_PATH . '/media', XOOPS_URL . '/media'), $instance->paths['media']);
        $this->assertSame(array(XOOPS_PATH, XOOPS_URL . '/browse.php'), $instance->paths['XOOPS']);
        $this->assertSame(array(XOOPS_PATH, XOOPS_URL . '/browse.php'), $instance->paths['XOOPS']);

        $this->assertTrue(is_null($instance->sess_handler));
        $this->assertTrue(is_null($instance->module));
        $this->assertTrue(is_array($instance->config));
        $this->assertTrue(is_array($instance->moduleConfig));
        $this->assertTrue(is_string($instance->moduleDirname));
        $this->assertTrue(is_string($instance->user) OR is_object($instance->user));
        $this->assertTrue(is_bool($instance->userIsAdmin));
        $this->assertTrue(is_null($instance->option) OR is_array($instance->option));
        $this->assertTrue(is_string($instance->tpl_name));
        $this->assertTrue(is_bool($instance->isAdminSide));
    }

    public function test_getInstance200()
	{
        $instance = Xoops::getInstance();

        $db = $instance->db();
        $this->assertInstanceOf('\Xoops\Core\Database\Connection', $db);

        $db1 = $instance->db();
        $this->assertSame($db, $db1);
    }

    public function test_preload()
	{
        $instance = Xoops::getInstance();

        $value = $instance->preload();
        $this->assertInstanceOf('\Xoops\Core\Events', $value);

        $value1 = $instance->preload();
        $this->assertSame($value, $value1);
    }

    public function test_registry()
	{
        $instance = Xoops::getInstance();

        $value = $instance->registry();
        $this->assertInstanceOf('Xoops_Registry', $value);

        $value1 = $instance->registry();
        $this->assertSame($value, $value1);
    }

    public function test_security()
	{
        $instance = Xoops::getInstance();

        $value = $instance->security();
        $this->assertInstanceOf('\Xoops\Core\Security', $value);

        $value1 = $instance->security();
        $this->assertSame($value, $value1);
    }

    public function test_setTpl()
	{
        $instance = Xoops::getInstance();

        $tpl = new XoopsTpl();

        $value = $instance->setTpl($tpl);
        $this->assertSame($tpl, $value);

        $value1 = $instance->Tpl();
        $this->assertSame($value, $value1);
    }

    public function test_setTheme()
	{
        $instance = Xoops::getInstance();

        $value = $instance->theme();
        $this->assertInstanceOf('XoopsTheme', $value);

        $theme = new XoopsTheme();
        $value = $instance->setTheme($theme);
        $this->assertSame($theme, $value);

        $value = $instance->theme();
        $this->assertSame($theme, $value);

        $value = $instance->theme('default');
        $this->assertInstanceOf('XoopsTheme', $value);

        $value = $instance->loadLocale('system');
        $this->assertSame(true, $value);

        $value = $instance->loadLocale('system/themes/default');
        $this->assertSame(true, $value);

        require_once XOOPS_ROOT_PATH . '/modules/system/themes/default/locale/en_US/en_US.php';
        require_once XOOPS_ROOT_PATH . '/modules/system/themes/default/locale/en_US/locale.php';

        /*
        $instance->setTheme(null);
        $instance->isAdminSide = true;
        $value = $instance->theme();
        $this->assertInstanceOf('XoopsTheme', $value);
        */

        $value = $instance->theme('default');
        $this->assertInstanceOf('XoopsTheme', $value);
    }

    public function test_path()
	{
        $instance = Xoops::getInstance();

        $value = $instance->path('class');
        $this->assertEquals('class', basename($value));
        $path = str_replace('/', DIRECTORY_SEPARATOR, XOOPS_ROOT_PATH);
        $this->assertEquals($path, dirname($value));
    }

    public function test_url()
	{
        $instance = Xoops::getInstance();

        $value = $instance->url('http://localhost/tmp/');
        $this->assertSame('http://localhost/tmp/', $value);

        $value = $instance->url('tmp');
        $this->assertSame(XOOPS_URL.'/tmp', $value);
    }

    public function test_buildUrl()
	{
        $instance = Xoops::getInstance();

        $value = $instance->buildUrl('http://localhost/tmp/');
        $this->assertSame('http://localhost/tmp/', $value);

        $value = $instance->buildUrl('.');
        $this->assertSame($_SERVER['REQUEST_URI'], $value);

        $url = 'http://localhost/tmp/test.php?toto=1';
        $value = $instance->buildUrl($url);
        $this->assertSame($url, $value);

        $url = 'http://localhost/tmp/test.php?toto=1';
        $value = $instance->buildUrl($url, array('titi'=>2));
        $this->assertSame($url.'&titi=2', $value);

        $url = 'http://localhost/tmp/test.php?toto=1';
        $value = $instance->buildUrl($url, array('titi'=>'space space'));
        $this->assertSame($url.'&titi=space%20space', $value);

        $url = 'http://localhost/tmp/test.php?toto=1';
        $value = $instance->buildUrl($url, array('toto'=>2));
        $this->assertSame('http://localhost/tmp/test.php?toto=2', $value);
    }

    /**
     * @ expectedException PHPUnit_Framework_Error
     */
    public function test_pathExists()
	{
        $instance = Xoops::getInstance();

		$value = $instance->pathExists('', E_USER_NOTICE);
		$this->assertSame(false, $value);
	}

    public function test_pathExists100()
	{
        $instance = Xoops::getInstance();

		$value = $instance->pathExists(XOOPS_ROOT_PATH, E_USER_NOTICE);
		$this->assertSame(XOOPS_ROOT_PATH, $value);
	}

    public function test_gzipcompression()
	{
        $instance = Xoops::getInstance();

        $save = $_SERVER['SERVER_NAME'];
        $_SERVER['SERVER_NAME'] = null;
        $instance->gzipCompression();
        $_SERVER['SERVER_NAME'] = $save;
        $value = $instance->getConfig('gzip_compression');
        $this->assertSame(0, $value);
    }

    public function test_pathTranslation()
	{
        $instance = Xoops::getInstance();

        $save = $_SERVER;

        $_SERVER['PATH_TRANSLATED'] = null;
        $_SERVER['SCRIPT_FILENAME'] = 'toto';
        $instance->pathTranslation();
        $this->assertSame($_SERVER['SCRIPT_FILENAME'], $_SERVER['PATH_TRANSLATED']);

        $_SERVER['PATH_TRANSLATED'] = 'toto';
        $_SERVER['SCRIPT_FILENAME'] = null;
        $instance->pathTranslation();
        $this->assertSame($_SERVER['SCRIPT_FILENAME'], $_SERVER['PATH_TRANSLATED']);

        $_SERVER['REQUEST_URI'] = null;
        $_SERVER['QUERY_STRING'] = null;
        $_SERVER['PHP_SELF'] = 'toto';
        $instance->pathTranslation();
        $this->assertSame($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF']);

        $_SERVER['REQUEST_URI'] = null;
        $_SERVER['QUERY_STRING'] = null;
        $_SERVER['PHP_SELF'] = null;
        $_SERVER['SCRIPT_NAME'] = 'titi';
        $instance->pathTranslation();
        $this->assertSame($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']);

        $_SERVER['REQUEST_URI'] = null;
        $_SERVER['QUERY_STRING'] = 'query=1';
        $_SERVER['PHP_SELF'] = 'toto';
        $instance->pathTranslation();
        $this->assertSame($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);

        $_SERVER = $save;
    }

    public function test_themeSelect()
	{
        $instance = Xoops::getInstance();

		$save1 = isset($_SESSION['xoopsUserTheme']) ? $_SESSION['xoopsUserTheme'] : null;
		$save2 = isset($_POST['xoops_theme_select']) ? $_POST['xoops_theme_select'] : null;
		$_SESSION['xoopsUserTheme'] = null;
		$_POST['xoops_theme_select'] = 'default';
		$instance->themeSelect();
		$value = $instance->getConfig('theme_set');
		$this->assertSame($_POST['xoops_theme_select'], $value);
		$this->assertSame($_SESSION['xoopsUserTheme'], $value);
		$_SESSION['xoopsUserTheme'] = $save1;
		$_POST['xoops_theme_select'] = $save2;

		$save1 = isset($_SESSION['xoopsUserTheme']) ? $_SESSION['xoopsUserTheme'] : null;
		$save2 = isset($_POST['xoops_theme_select']) ? $_POST['xoops_theme_select'] : null;
		$_SESSION['xoopsUserTheme'] = 'default';
		$_POST['xoops_theme_select'] = null;
		$instance->themeSelect();
		$value = $instance->getConfig('theme_set');
		$this->assertSame($_SESSION['xoopsUserTheme'], $value);
		$_SESSION['xoopsUserTheme'] = $save1;
		$_POST['xoops_theme_select'] = $save2;

	}

    public function test_getTplInfo()
	{
        $instance = Xoops::getInstance();

        $path = 'path';
        $value = $instance->getTplInfo($path);
        $this->assertSame('module', $value['type']);
        $this->assertSame('system', $value['module']);
        $this->assertSame('path', $value['file']);
        $this->assertSame('module:system|path', $value['tpl_name']);

        $path = 'db:path';
        $value = $instance->getTplInfo($path);
        $this->assertSame('module', $value['type']);
        $this->assertSame('system', $value['module']);
        $this->assertSame('path', $value['file']);
        $this->assertSame('module:system|path', $value['tpl_name']);
    }

    public function test_header()
	{
        $instance = Xoops::getInstance();

        $value = $instance->header();
        $this->assertSame(true, $value);

        $value = $instance->header();
        $this->assertSame(false, $value);
    }

    public function test_footer()
	{
        $instance = Xoops::getInstance();

		//$value = $instance->footer();
		$this->markTestIncomplete();
	}

    public function test_isModule()
	{
        $instance = Xoops::getInstance();

        $value = $instance->isModule();
        $this->assertSame(false, $value);

        $module = new XoopsModule();
        $instance->module = $module;
        $value = $instance->isModule();
        $this->assertSame(true, $value);
    }

    public function test_isUser()
	{
        $instance = Xoops::getInstance();

        $value = $instance->isUser();
        $this->assertSame(false, $value);
    }

    public function test_isAdmin()
	{
        $instance = Xoops::getInstance();

        $value = $instance->isAdmin();
        $this->assertSame(false, $value);
    }

    public function test_request()
	{
        $instance = Xoops::getInstance();

        $value = $instance->request();
        $this->assertInstanceOf('Xoops_Request_Http', $value);
    }

    public function test_getHandlerBlock()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerBlock();
        $this->assertInstanceOf('XoopsBlockHandler', $value);
    }

    public function test_getHandlerBlockmodulelink()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerBlockmodulelink();
        $this->assertInstanceOf('XoopsBlockmodulelinkHandler', $value);
    }

    public function test_getHandlerCachemodel()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerCachemodel();
        $this->assertInstanceOf('XoopsCachemodelHandler', $value);
    }

    public function test_getHandlerConfig()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerConfig();
        $this->assertInstanceOf('XoopsConfigHandler', $value);
    }

	/* getHandlerConfigcategory no longer exists
    public function test_getHandlerConfigcategory()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerConfigcategory();
        $this->assertInstanceOf('XoopsConfigCategoryHandler', $value);
    }
    */

    public function test_getHandlerConfigitem()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerConfigitem();
        $this->assertInstanceOf('XoopsConfigItemHandler', $value);
    }

    public function test_getHandlerConfigoption()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerConfigoption();
        $this->assertInstanceOf('XoopsConfigOptionHandler', $value);
    }

    public function test_getHandlerGroup()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerGroup();
        $this->assertInstanceOf('XoopsGroupHandler', $value);
    }

    public function test_getHandlerGroupperm()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerGroupperm();
        $this->assertInstanceOf('XoopsGrouppermHandler', $value);
    }

    public function test_getHandlerMember()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerMember();
        $this->assertInstanceOf('XoopsMemberHandler', $value);
    }

    public function test_getHandlerMembership()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerMembership();
        $this->assertInstanceOf('XoopsMembershipHandler', $value);
    }

    public function test_getHandlerModule()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerModule();
        $this->assertInstanceOf('XoopsModuleHandler', $value);
    }

    public function test_getHandlerOnline()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerOnline();
        $this->assertInstanceOf('XoopsOnlineHandler', $value);
    }

    public function test_getHandlerPrivmessage()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerPrivmessage();
        $this->assertInstanceOf('XoopsPrivmessageHandler', $value);
    }

    public function test_getHandlerRanks()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerRanks();
        $this->assertInstanceOf('XoopsRanksHandler', $value);
    }

    public function test_getHandlerSession()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerSession();
        $this->assertInstanceOf('XoopsSessionHandler', $value);
    }

    public function test_getHandlerTplfile()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerTplfile();
        $this->assertInstanceOf('XoopsTplfileHandler', $value);
    }

    public function test_getHandlerTplset()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerTplset();
        $this->assertInstanceOf('XoopsTplsetHandler', $value);
    }

    public function test_getHandlerUser()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getHandlerUser();
        $this->assertInstanceOf('XoopsUserHandler', $value);
    }

    /**
     * @ expectedException PHPUnit_Framework_Error
     */
    public function test_getHandler()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandler('user');
		$this->assertInstanceOf('XoopsUserHandler', $value);
		$value = $instance->getHandler('dummy', true);
		$this->assertSame(false, $value);
	}

    public function test_getModuleHandler()
	{
        $instance = Xoops::getInstance();

        $instance->module = new XoopsModule();
        $value = $instance->getModuleHandler('page_content', 'page');
        $this->assertTrue(is_object($value));
    }

    public function test_getModuleForm()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getModuleForm(null, null);
        $this->assertSame(false, $value);
    }

    public function test_getModuleHelper()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getModuleHelper('page');
        $this->assertInstanceOf('Page', $value);
    }

    public function test_loadLanguage()
	{
        $instance = Xoops::getInstance();

        $value = $instance->loadLanguage(null);
        $this->assertSame(false, $value);

        $value = $instance->loadLanguage('_errors', null, 'english');
        $this->assertTrue(!empty($value));
    }

    public function test_loadLocale()
	{
        $instance = Xoops::getInstance();

        $value = $instance->loadLocale();
        $this->assertSame(true, $value);
    }

    public function test_translate()
	{
        $instance = Xoops::getInstance();

        $value = $instance->translate(XoopsLocale::ABOUT);
        $this->assertSame('About', $value);
    }

    public function test_getActiveModules()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getActiveModules();
        $this->assertTrue(is_array($value) AND count($value)>0);
    }

    public function test_setActiveModules()
	{
        $instance = Xoops::getInstance();

        $value = $instance->setActiveModules();
        $this->assertTrue(is_array($value) AND count($value)>0);
    }

    public function test_isActiveModule()
	{
        $instance = Xoops::getInstance();

        $value = $instance->isActiveModule('page');
        $this->assertSame(true, $value);

        $value = $instance->isActiveModule(null);
        $this->assertSame(false, $value);
    }

    public function test_getModuleByDirname()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getModuleByDirname('page');
        $this->assertinstanceOf('XoopsModule', $value);
        $this->assertSame('Page', $value->name());

        $value = $instance->getModuleByDirname('dummy');
        $this->assertSame(false, $value);
    }

    public function test_getModuleById()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getModuleById(1);
        $this->assertInstanceOf('XoopsModule', $value);

        $value = $instance->getModuleById(-1);
        $this->assertSame(false, $value);
    }

    public function test_simpleHeader()
	{
        $instance = Xoops::getInstance();

        ob_start();
        $instance->simpleHeader();
        $value = ob_get_contents();
        ob_end_clean();
        $this->assertTrue(is_string($value));
    }

    public function test_simpleFooter()
    {
        $xoops = Xoops::getInstance();

        $output = null;

        $callback =
            function ($buffer) use (&$output) {
                $output = $buffer;
                return '';
            };

        ob_start($callback);
        $xoops->simpleFooter();
        $this->assertTrue(is_string($output), $output);
        $this->assertSame(substr($output,-7), '</html>', $output);
        //$this->markTestSkipped('');
    }

    public function test_alert()
	{
        $instance = Xoops::getInstance();

        $value = $instance->alert('info', '');
        $this->assertSame('', $value);

        $msg = 'alert_info';
        $value = $instance->alert('info', $msg);
        $this->assertTrue(is_string($value));

        $msg = 'alert_info';
        $value = $instance->alert('dummy', $msg);
        $this->assertTrue(is_string($value));

        $msg = 'alert_error';
        $value = $instance->alert('error', $msg);
        $this->assertTrue(is_string($value));

        $msg = 'alert_success';
        $value = $instance->alert('success', $msg);
        $this->assertTrue(is_string($value));

        $msg = 'alert_warning';
        $value = $instance->alert('warning', $msg);
        $this->assertTrue(is_string($value));

        $msg = new XoopsModule();
        $value = $instance->alert('warning', $msg);
        $this->assertTrue(is_string($value));

        $msg = array('text_1', 'text_2');
        $value = $instance->alert('warning', $msg);
        $this->assertTrue(is_string($value));
    }

    public function test_error()
	{
        $instance = Xoops::getInstance();

        ob_start();
        $instance->error('test');
        $value = ob_get_contents();
        ob_end_clean();
        $this->assertTrue(is_string($value));
    }

    public function test_result()
	{
        $instance = Xoops::getInstance();

        ob_start();
        $instance->result('test');
        $value = ob_get_contents();
        ob_end_clean();
        $this->assertTrue(is_string($value));
    }

    public function test_confirm()
	{
        $instance = Xoops::getInstance();

		defined('NWLINE') OR define('NWLINE', "\n");
		ob_start();
		$instance->confirm(array(),array(),'msg');
		$value = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(is_string($value) AND strlen($value)>0);

		ob_start();
		$instance->confirm(array('toto'=>1,'tutu'=>2),array(),'msg');
		$value = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(is_string($value) AND strlen($value)>0);

		ob_start();
		$instance->confirm(array('toto'=>1, 'tutu'=>array('t1'=>11, 't2'=>22)),array(),'msg');
		$value = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(is_string($value) AND strlen($value)>0);
	}

    public function test_getUserTimestamp()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getUserTimestamp(time());
        $this->assertTrue(is_int($value));

        $instance->user = new XoopsUser();
        $instance->user->setVar('timezone_offset', 10);
        $value = $instance->getUserTimestamp(time());
        $this->assertTrue(is_int($value));
    }

    public function test_userTimeToServerTime()
	{
        $instance = Xoops::getInstance();

        $value = $instance->userTimeToServerTime(time());
        $this->assertTrue(is_int($value));
    }

    public function test_makePass()
	{
        $instance = Xoops::getInstance();

        $value = $instance->makePass();
        $this->assertTrue(is_string($value));
        $value = $instance->makePass();
        $this->assertTrue(is_string($value));
        $value = $instance->makePass();
        $this->assertTrue(is_string($value));
        $value = $instance->makePass();
        $this->assertTrue(is_string($value));
    }

    public function test_checkEmail()
	{
        $instance = Xoops::getInstance();

        $value = $instance->checkEmail(null);
        $this->assertSame(false, $value);

        $email = 'test@test.com';
        $value = $instance->checkEmail($email);
        $this->assertSame($email, $value);

        $email = 'test@test.com';
        $value = $instance->checkEmail($email, true);
        $this->assertSame('test at test dot com', $value);

        $email = 'test\toto.tutu.titi@test.com';
        $value = $instance->checkEmail($email);
        $this->assertSame(false, $value);

        $email = 'test@test';
        $value = $instance->checkEmail($email);
        $this->assertSame(false, $value);

        $email = 'test@test\titi.com';
        $value = $instance->checkEmail($email);
        $this->assertSame(false, $value);

        // some test cases taken from https://github.com/dominicsayers/isemail
        $this->assertFalse($instance->checkEmail('test@iana..com'));
        $this->assertFalse($instance->checkEmail('abc@def@iana.org'));
        $this->assertFalse($instance->checkEmail('@iana.org'));
        $this->assertFalse($instance->checkEmail('abc@'));

        $email = 'user+mailbox@iana.org';
        $this->assertSame($email, $instance->checkEmail($email));

        $email = 'dclo@us.ibm.com';
        $this->assertSame($email, $instance->checkEmail($email));

        $email = 'valid@about.museum';
        $this->assertSame($email, $instance->checkEmail($email));
    }

    public function test_formatURL()
	{
        $instance = Xoops::getInstance();

        $url = 'http://localhost/xoops';
        $value = $instance->formatURL($url);
        $this->assertSame($url, $value);

        $url = 'https://localhost/xoops';
        $value = $instance->formatURL($url);
        $this->assertSame($url, $value);

        $url = 'ftp://localhost/xoops';
        $value = $instance->formatURL($url);
        $this->assertSame($url, $value);

        $url = 'ftps://localhost/xoops';
        $value = $instance->formatURL($url);
        $this->assertSame($url, $value);

        $url = 'ed2k://localhost/xoops';
        $value = $instance->formatURL($url);
        $this->assertSame($url, $value);

        $url = 'localhost/xoops';
        $value = $instance->formatURL($url);
        $this->assertSame('http://'.$url, $value);
    }

    public function test_getBanner()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getBanner();
        $this->assertTrue(is_string($value));
    }

    public function test_redirect()
	{
        $instance = Xoops::getInstance();

		//$value = $instance->redirect();
		$this->markTestIncomplete('');
	}

    public function test_getEnv()
	{
        $instance = Xoops::getInstance();

        $_SERVER['DUMMY'] = 'dummy';
        $value = $instance->getEnv('DUMMY');
        $this->assertSame('dummy', $value);
        unset($_SERVER['DUMMY']);

        $_ENV['DUMMY'] = 'dummy';
        $value = $instance->getEnv('DUMMY');
        $this->assertSame('dummy', $value);
        unset($_ENV['DUMMY']);

        unset($_ENV['DUMMY'], $_SERVER['DUMMY']);
        $value = $instance->getEnv('DUMMY');
        $this->assertSame('', $value);
    }

    public function test_getCss()
	{
        $instance = Xoops::getInstance();

        $save = $_SERVER['HTTP_USER_AGENT'];

        $value = $instance->getCss();
        $this->assertTrue(is_string($value));

        $_SERVER['HTTP_USER_AGENT'] = 'mac';
        $value = $instance->getCss();
        $this->assertSame(XOOPS_THEME_URL . '/default/css/style.css', $value);

        $_SERVER['HTTP_USER_AGENT'] = 'MSIE 1.2';
        $value = $instance->getCss();
        $this->assertSame(XOOPS_THEME_URL . '/default/css/style.css', $value);

        $_SERVER['HTTP_USER_AGENT'] = 'MSIE 1.2';
        $value = $instance->getCss('default');
        $this->assertTrue(basename($value) == 'style.css');
        $this->assertTrue(basename(dirname($value)) == 'css');

        $_SERVER['HTTP_USER_AGENT'] = 'XXXX';
        $value = $instance->getCss('default');
        $this->assertTrue(basename($value) == 'style.css');
        $this->assertTrue(basename(dirname($value)) == 'css');

        $_SERVER['HTTP_USER_AGENT'] = 'MSIE 1.2';
        $value = $instance->getCss('default/css');
        $this->assertTrue(basename($value) == 'style.css');
        $this->assertTrue(basename(dirname($value)) == 'css');

        $_SERVER['HTTP_USER_AGENT'] = 'XXXX';
        $value = $instance->getCss('default/css');
        $this->assertTrue(basename($value) == 'style.css');
        $this->assertTrue(basename(dirname($value)) == 'css');

        $_SERVER['HTTP_USER_AGENT'] = 'XXXX';
        $value = $instance->getCss('xxxx');
        $this->assertSame('', $value);

        $_SERVER['HTTP_USER_AGENT'] = $save;
    }

    public function test_getMailer()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getMailer();
        $this->assertTrue($value instanceOf XoopsMailerLocale OR $value instanceOf XoopsMailer);
    }

    public function test_getRank()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getRank();
        $this->assertTrue(is_array($value));

        $value = $instance->getRank(1);
        $this->assertTrue(is_array($value));
    }

    public function test_getOption()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getOption('dummy');
        $this->assertSame('', $value);
    }

    public function test_setOption()
	{
        $instance = Xoops::getInstance();

        $instance->setOption('dummy', null);
        $value = $instance->getOption('dummy');
        $this->assertSame('', $value);

        $instance->setOption('dummy', 'dummy');
        $value = $instance->getOption('dummy');
        $this->assertSame('dummy', $value);
    }

    public function test_getConfig()
	{
        $instance = Xoops::getInstance();

        $invalidKey = md5(uniqid());
        $value = $instance->getConfig($invalidKey);
        $this->assertSame('', $value);
    }

    public function test_getConfigs()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getConfigs();
        $this->assertTrue(is_array($value));
    }

    public function test_addConfigs()
	{
        $instance = Xoops::getInstance();

        $instance->addConfigs(array('dummy' => 1));
        $value = $instance->getConfigs();
        $this->assertTrue(is_array($value));
        $this->assertTrue(isset($value['dummy']) AND $value['dummy'] = 1);

        $instance->addConfigs(array('dummy' => 1), null);
        $value = $instance->getConfigs();
        $this->assertTrue(is_array($value));
        $this->assertTrue(isset($value['dummy']) AND $value['dummy'] = 1);
    }

    public function test_setConfig()
	{
        $instance = Xoops::getInstance();

        $instance->setConfig('dummy', 1);
        $value = $instance->getConfig('dummy');
        $this->assertSame(1, $value);

        $instance->setConfig('dummy', 1, null);
        $value = $instance->getConfig('dummy');
        $this->assertSame(1, $value);
    }

    public function test_appendConfig()
	{
        $instance = Xoops::getInstance();

        $instance->appendConfig('dummy', array('test'=>1), true);
        $value = $instance->getConfig('dummy');
        $this->assertSame(1, $value['test']);

        $instance->appendConfig('dummy', array('test'=>1), false);
        $value = $instance->getConfig('dummy');

        $instance->appendConfig('dummy', array('test'=>1), true, null);
        $value = $instance->getConfig('dummy');
        $this->assertSame(1, $value['test']);
    }

    public function test_getModuleConfig()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getModuleConfig(uniqid());
        $this->assertTrue(empty($value));
    }

    public function test_getModuleConfigs()
	{
        $instance = Xoops::getInstance();

        $value = $instance->getModuleConfigs();
        $this->assertTrue(is_array($value));
    }

    public function test_disableModuleCache()
	{
        $instance = Xoops::getInstance();

        $instance->disableModuleCache();
    }

    public function test_getBaseDomain()
    {
        $xoops = Xoops::getInstance();

        // test cases url, expected base return (default), expected full return ($includeSubdomain==true)
        $urls = array(
            array('url' => 'http://user:pass@www.pref.okinawa.jp:8080/path/to/page.html?query=string#fragment', 'base' => 'pref.okinawa.jp', 'full' => 'www.pref.okinawa.jp',),
            array('url' => 'http://localhost/test/domain/', 'base' => 'localhost', 'full' => 'localhost',),
            array('url' => 'https://192.168.1.251/xoops/', 'base' => '192.168.1.251', 'full' => '192.168.1.251',),
            array('url' => 'http://WWW.PREF.OKINAWA.JP/', 'base' => 'pref.okinawa.jp', 'full' => 'www.pref.okinawa.jp',),
            array('url' => 'http://www.example.com/', 'base' => 'example.com', 'full' => 'www.example.com',),
            array('url' => 'http://fred.users.example.com/', 'base' => 'example.com', 'full' => 'fred.users.example.com',),
            array('url' => 'ftp://example.com', 'base' => 'example.com', 'full' => 'example.com',),
            array('url' => 'https://www.scottwills.co.uk/', 'base' => 'scottwills.co.uk', 'full' => 'www.scottwills.co.uk',),
            array('url' => 'http://co.uk/', 'base' => null, 'full' => null,),
            array('url' => 'http://xoops.consulting', 'base' => 'xoops.consulting', 'full' => 'xoops.consulting',),
            array('url' => 'okinawa.jp', 'base' => null, 'full' => null,),
            array('url' => 'http://இலங்கை.museum', 'base' => 'இலங்கை.museum', 'full' => 'இலங்கை.museum',),
            array('url' => 'http://россия.net', 'base' => 'россия.net', 'full' => 'россия.net',),
            array('url' => 'http://私の団体も.jp/', 'base' => '私の団体も.jp', 'full' => '私の団体も.jp',),
            array('url' => 'https://中国化工集团公司.公司:8080/test', 'base' => '中国化工集团公司.公司', 'full' => '中国化工集团公司.公司',),
            array('url' => '公司', 'base' => null, 'full' => null,),
            array('url' => 'http://321.4.1.512/', 'base' => '1.512', 'full' => '321.4.1.512',),
        // ipv6 examples from http://www.ietf.org/rfc/rfc2732.txt
            array('url' => 'https://[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80/index.html', 'base' => 'fedc:ba98:7654:3210:fedc:ba98:7654:3210', 'full' => 'fedc:ba98:7654:3210:fedc:ba98:7654:3210',),
            array('url' => 'http://[1080:0:0:0:8:800:200C:417A]/index.html', 'base' => '1080:0:0:0:8:800:200c:417a', 'full' => '1080:0:0:0:8:800:200c:417a',),
            array('url' => 'http://[3ffe:2a00:100:7031::1]', 'base' => '3ffe:2a00:100:7031::1', 'full' => '3ffe:2a00:100:7031::1',),
            array('url' => 'http://[1080::8:800:200C:417A]/foo', 'base' => '1080::8:800:200c:417a', 'full' => '1080::8:800:200c:417a',),
            array('url' => 'http://[::192.9.5.5]/ipng', 'base' => '::192.9.5.5', 'full' => '::192.9.5.5',),
            array('url' => 'http://[::FFFF:129.144.52.38]:80/index.html', 'base' => '::ffff:129.144.52.38', 'full' => '::ffff:129.144.52.38',),
            array('url' => 'http://[2010:836B:4179::836B:4179]', 'base' => '2010:836b:4179::836b:4179', 'full' => '2010:836b:4179::836b:4179',),
        );
        foreach ($urls as $url) {
            $this->assertSame($xoops->getBaseDomain($url['url']), $url['base'], $url['url']);
            $this->assertSame($xoops->getBaseDomain($url['url'], true), $url['full'], $url['url']);
        }
    }

    public function test_getUrlDomain()
	{
        $instance = Xoops::getInstance();

        $url = 'http://username:password@hostname/path?arg=value#anchor';
        $value = $instance->getUrlDomain($url);
        $this->assertSame('hostname', $value);

        $url = 'localhost.php';
        $value = $instance->getUrlDomain($url);
        $this->assertSame('', $value);
    }

    public function test_templateTouch()
	{
        $instance = Xoops::getInstance();

        $value = $instance->templateTouch(1);
        $this->assertSame(true, $value);
    }

    public function test_templateClearModuleCache()
	{
        $instance = Xoops::getInstance();

        $instance->templateClearModuleCache(1);
    }

    public function test_deprecated()
	{
        $instance = Xoops::getInstance();

        $instance->deprecated('message');
    }

    public function test_disableErrorReporting()
	{
        $instance = Xoops::getInstance();

        $instance->disableErrorReporting();
    }
}
