<?php
require_once(dirname(__FILE__).'/../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsTest extends MY_UnitTestCase
{

    public function SetUp()
	{
    }

    public function test_100()
	{
        $instance = Xoops::getInstance();
		$this->assertInstanceOf('Xoops', $instance);

        $instance2=Xoops::getInstance();
		$this->assertSame($instance, $instance2);

		// First initialization in first test
		if (!class_exists('Xoops_Locale',false)) {
			$value = $instance->loadLocale();
			$this->assertSame(true, $value);
		}

		$this->assertSame(array(XOOPS_PATH, XOOPS_URL . 'browse.php'), $instance->paths['XOOPS']);
		$this->assertSame(array(XOOPS_ROOT_PATH, XOOPS_URL), $instance->paths['www']);
		$this->assertSame(array(XOOPS_VAR_PATH, null), $instance->paths['var']);
		$this->assertSame(array(XOOPS_PATH, XOOPS_URL . 'browse.php'), $instance->paths['lib']);
		$this->assertSame(array(XOOPS_ROOT_PATH . '/modules', XOOPS_URL . '/modules'), $instance->paths['modules']);
		$this->assertSame(array(XOOPS_ROOT_PATH . '/themes', XOOPS_URL . '/themes'), $instance->paths['themes']);
		$this->assertSame(array(XOOPS_ROOT_PATH . '/media', XOOPS_URL . '/media'), $instance->paths['media']);
		$this->assertSame(array(XOOPS_PATH, XOOPS_URL . 'browse.php'), $instance->paths['XOOPS']);
		$this->assertSame(array(XOOPS_PATH, XOOPS_URL . 'browse.php'), $instance->paths['XOOPS']);

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

    public function test_200()
	{
        $instance = Xoops::getInstance();

		$db = $instance->db();
		$this->assertInstanceOf('\Xoops\Core\Database\Connection', $db);

		$db1 = $instance->db();
		$this->assertSame($db, $db1);
	}

    public function test_300()
	{
        $instance = Xoops::getInstance();

		$value = $instance->preload();
		$this->assertInstanceOf('\Xoops\Core\Events', $value);

		$value1 = $instance->preload();
		$this->assertSame($value, $value1);
	}

    public function test_400()
	{
        $instance = Xoops::getInstance();

		$value = $instance->registry();
		$this->assertInstanceOf('Xoops_Registry', $value);

		$value1 = $instance->registry();
		$this->assertSame($value, $value1);
	}

    public function test_500()
	{
        $instance = Xoops::getInstance();

		$value = $instance->security();
		$this->assertInstanceOf('XoopsSecurity', $value);

		$value1 = $instance->security();
		$this->assertSame($value, $value1);
	}

    public function test_600()
	{
        $instance = Xoops::getInstance();

		$tpl = new XoopsTpl();

		$value = $instance->setTpl($tpl);
		$this->assertSame($tpl, $value);

		$value1 = $instance->Tpl();
		$this->assertSame($value, $value1);
	}

    public function test_700()
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

    public function test_800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->path('class');
		$this->assertEquals('class', basename($value));
		$path = str_replace('/',DIRECTORY_SEPARATOR,XOOPS_ROOT_PATH);
		$this->assertEquals($path, dirname($value));
	}

    public function test_900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->url('http://localhost/tmp/');
		$this->assertSame('http://localhost/tmp/', $value);

		$value = $instance->url('tmp');
		$this->assertSame(XOOPS_URL.'/tmp', $value);
	}

    public function test_1000()
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
		$value = $instance->buildUrl($url,array('titi'=>2));
		$this->assertSame($url.'&titi=2', $value);

		$url = 'http://localhost/tmp/test.php?toto=1';
		$value = $instance->buildUrl($url,array('titi'=>'space space'));
		$this->assertSame($url.'&titi=space%20space', $value);

		$url = 'http://localhost/tmp/test.php?toto=1';
		$value = $instance->buildUrl($url,array('toto'=>2));
		$this->assertSame('http://localhost/tmp/test.php?toto=2', $value);
	}

    public function test_1100()
	{
        $instance = Xoops::getInstance();

		$this->expectError();
		if (defined('IS_PHPUNIT')) {
			$NoticeEnabledOrig = PHPUnit_Framework_Error_Notice::$enabled;
			PHPUnit_Framework_Error_Notice::$enabled = false;
		}
		$value = $instance->pathExists('', E_USER_NOTICE);
		$this->assertSame(false, $value);
		if (defined('IS_PHPUNIT')) {
			PHPUnit_Framework_Error_Notice::$enabled = $NoticeEnabledOrig;
		}

		$value = $instance->pathExists(XOOPS_ROOT_PATH, E_USER_NOTICE);
		$this->assertSame(XOOPS_ROOT_PATH, $value);
	}

    public function test_1200()
	{
        $instance = Xoops::getInstance();

		$save = $_SERVER['SERVER_NAME'];
		$_SERVER['SERVER_NAME'] = null;
		$instance->gzipCompression();
		$_SERVER['SERVER_NAME'] = $save;
		$value = $instance->getConfig('gzip_compression');
		$this->assertSame(0, $value);
	}

    public function test_1300()
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

    public function test_1400()
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

    public function test_1500()
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

    public function test_1600()
	{
        $instance = Xoops::getInstance();

		$value = $instance->header();
		$this->assertSame(true, $value);

		$value = $instance->header();
		$this->assertSame(false, $value);
	}

    public function test_1700()
	{
        $instance = Xoops::getInstance();

		//$value = $instance->footer();
		$this->markTestSkipped('');
	}

    public function test_1800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->isModule();
		$this->assertSame(false, $value);

		$module = new XoopsModule();
		$instance->module = $module;
		$value = $instance->isModule();
		$this->assertSame(true, $value);
	}

    public function test_1900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->isUser();
		$this->assertSame(false, $value);
	}

    public function test_2000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->isAdmin();
		$this->assertSame(false, $value);
	}

    public function test_2100()
	{
        $instance = Xoops::getInstance();

		$value = $instance->request();
		$this->assertInstanceOf('Xoops_Request_Http', $value);
	}

    public function test_2200()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerBlock();
		$this->assertInstanceOf('XoopsBlockHandler', $value);
	}

    public function test_2300()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerBlockmodulelink();
		$this->assertInstanceOf('XoopsBlockmodulelinkHandler', $value);
	}

    public function test_2400()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerCachemodel();
		$this->assertInstanceOf('XoopsCachemodelHandler', $value);
	}

    public function test_2500()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerConfig();
		$this->assertInstanceOf('XoopsConfigHandler', $value);
	}

	/* getHandlerConfigcategory no longer exists
    public function test_2600()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerConfigcategory();
		$this->assertInstanceOf('XoopsConfigCategoryHandler', $value);
	}
	*/

    public function test_2700()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerConfigitem();
		$this->assertInstanceOf('XoopsConfigItemHandler', $value);
	}

    public function test_2800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerConfigoption();
		$this->assertInstanceOf('XoopsConfigOptionHandler', $value);
	}

    public function test_2900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerGroup();
		$this->assertInstanceOf('XoopsGroupHandler', $value);
	}

    public function test_3000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerGroupperm();
		$this->assertInstanceOf('XoopsGrouppermHandler', $value);
	}

    public function test_3100()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerMember();
		$this->assertInstanceOf('XoopsMemberHandler', $value);
	}

    public function test_3200()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerMembership();
		$this->assertInstanceOf('XoopsMembershipHandler', $value);
	}

    public function test_3300()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerModule();
		$this->assertInstanceOf('XoopsModuleHandler', $value);
	}

    public function test_3400()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerOnline();
		$this->assertInstanceOf('XoopsOnlineHandler', $value);
	}

    public function test_3500()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerPrivmessage();
		$this->assertInstanceOf('XoopsPrivmessageHandler', $value);
	}

    public function test_3600()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerRanks();
		$this->assertInstanceOf('XoopsRanksHandler', $value);
	}

    public function test_3700()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerSession();
		$this->assertInstanceOf('XoopsSessionHandler', $value);
	}

    public function test_3800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerTplfile();
		$this->assertInstanceOf('XoopsTplfileHandler', $value);
	}

    public function test_3900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerTplset();
		$this->assertInstanceOf('XoopsTplsetHandler', $value);
	}

    public function test_4000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandlerUser();
		$this->assertInstanceOf('XoopsUserHandler', $value);
	}

    public function test_4100()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getHandler('user');
		$this->assertInstanceOf('XoopsUserHandler', $value);
		PHPUnit_Framework_Error_Warning::$enabled = FALSE;
		$value = $instance->getHandler('dummy', true);
		$this->assertSame(false, $value);
	}

    public function test_4200()
	{
        $instance = Xoops::getInstance();

		$instance->module = new XoopsModule();
		$value = $instance->getModuleHandler('page_content', 'page');
		$this->assertTrue(is_object($value));
	}

    public function test_4300()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getModuleForm(null, null);
		$this->assertSame(false, $value);
	}

    public function test_4400()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getModuleHelper('page');
		$this->assertInstanceOf('Page', $value);
	}

    public function test_4500()
	{
        $instance = Xoops::getInstance();

		$value = $instance->loadLanguage(null);
		$this->assertSame(false, $value);

		$value = $instance->loadLanguage('_errors', null, 'english');
		$this->assertTrue(!empty($value));
	}

    public function test_4600()
	{
        $instance = Xoops::getInstance();

		$value = $instance->loadLocale();
		$this->assertSame(true, $value);
	}

    public function test_4700()
	{
        $instance = Xoops::getInstance();

		$value = $instance->translate(XoopsLocale::ABOUT);
		$this->assertSame('About', $value);
	}

    public function test_4800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getActiveModules();
		$this->assertTrue(is_array($value) AND count($value)>0);
	}

    public function test_4900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->setActiveModules();
		$this->assertTrue(is_array($value) AND count($value)>0);
	}

    public function test_5000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->isActiveModule('page');
		$this->assertSame(true, $value);

		$value = $instance->isActiveModule(null);
		$this->assertSame(false, $value);
	}

    public function test_5100()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getModuleByDirname('page');
		$this->assertinstanceOf('XoopsModule', $value);
		$this->assertSame('Page', $value->name());

		$value = $instance->getModuleByDirname('dummy');
		$this->assertSame(false, $value);
	}

    public function test_5200()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getModuleById(1);
		$this->assertInstanceOf('XoopsModule', $value);

		$value = $instance->getModuleById(-1);
		$this->assertSame(false, $value);
	}

    public function test_5300()
	{
        $instance = Xoops::getInstance();

		ob_start();
		$instance->simpleHeader();
		$value = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(is_string($value));
	}

    public function test_5400()
	{
        $instance = Xoops::getInstance();

		//$instance->simpleFooter();
		//$this->assertTrue(is_string($value));
		$this->markTestSkipped('');
	}

    public function test_5500()
	{
        $instance = Xoops::getInstance();

		$value = $instance->alert('info','');
		$this->assertSame('', $value);

		$msg = 'alert_info';
		$value = $instance->alert('info',$msg);
		$this->assertTrue(is_string($value));

		$msg = 'alert_info';
		$value = $instance->alert('dummy',$msg);
		$this->assertTrue(is_string($value));

		$msg = 'alert_error';
		$value = $instance->alert('error',$msg);
		$this->assertTrue(is_string($value));

		$msg = 'alert_success';
		$value = $instance->alert('success',$msg);
		$this->assertTrue(is_string($value));

		$msg = 'alert_warning';
		$value = $instance->alert('warning',$msg);
		$this->assertTrue(is_string($value));

		$msg = new XoopsModule();
		$value = $instance->alert('warning',$msg);
		$this->assertTrue(is_string($value));

		$msg = array('text_1', 'text_2');
		$value = $instance->alert('warning',$msg);
		$this->assertTrue(is_string($value));
	}

    public function test_5600()
	{
        $instance = Xoops::getInstance();

		ob_start();
		$instance->error('test');
		$value = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(is_string($value));
	}

    public function test_5700()
	{
        $instance = Xoops::getInstance();

		ob_start();
		$instance->result('test');
		$value = ob_get_contents();
		ob_end_clean();
		$this->assertTrue(is_string($value));
	}

    public function test_5800()
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

    public function test_5900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getUserTimestamp(time());
		$this->assertTrue(is_int($value));

		$instance->user = new XoopsUser();
		$instance->user->setVar('timezone_offset',10);
		$value = $instance->getUserTimestamp(time());
		$this->assertTrue(is_int($value));
	}

    public function test_6000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->userTimeToServerTime(time());
		$this->assertTrue(is_int($value));
	}

    public function test_6100()
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

    public function test_6200()
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
	}

    public function test_6300()
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

    public function test_6400()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getBanner();
		$this->assertTrue(is_string($value));
	}

    public function test_6500()
	{
        $instance = Xoops::getInstance();

		//$value = $instance->redirect();
		$this->markTestSkipped('');
	}

    public function test_6600()
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

    public function test_6700()
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

    public function test_6800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getMailer();
		$this->assertTrue($value instanceOf XoopsMailerLocale OR $value instanceOf XoopsMailer);
	}

    public function test_6900()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getRank();
		$this->assertTrue(is_array($value));

		$value = $instance->getRank(1);
		$this->assertTrue(is_array($value));
	}

    public function test_7000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getOption('dummy');
		$this->assertSame('', $value);
	}

    public function test_7100()
	{
        $instance = Xoops::getInstance();

		$instance->setOption('dummy',null);
		$value = $instance->getOption('dummy');
		$this->assertSame('', $value);

		$instance->setOption('dummy','dummy');
		$value = $instance->getOption('dummy');
		$this->assertSame('dummy', $value);
	}

    public function test_7200()
	{
        $instance = Xoops::getInstance();

		$invalidKey = md5( uniqid() );
		$value = $instance->getConfig($invalidKey);
		$this->assertSame('', $value);
	}

    public function test_7300()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getConfigs();
		$this->assertTrue(is_array($value));
	}

    public function test_7400()
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

    public function test_7500()
	{
        $instance = Xoops::getInstance();

		$instance->setConfig('dummy', 1);
		$value = $instance->getConfig('dummy');
		$this->assertSame(1, $value);

		$instance->setConfig('dummy', 1, null);
		$value = $instance->getConfig('dummy');
		$this->assertSame(1, $value);
	}

    public function test_7600()
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

    public function test_7700()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getModuleConfig(uniqid());
		$this->assertTrue(empty($value));
	}

    public function test_7800()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getModuleConfigs();
		$this->assertTrue(is_array($value));
	}

    public function test_7900()
	{
        $instance = Xoops::getInstance();

		$instance->disableModuleCache();
	}

    public function test_8000()
	{
        $instance = Xoops::getInstance();

		$value = $instance->getBaseDomain('http::/localhost/tmp');
		$this->assertSame('', $value);
	}

    public function test_8100()
	{
        $instance = Xoops::getInstance();

		$url = 'http://username:password@hostname/path?arg=value#anchor';
		$value = $instance->getUrlDomain($url);
		$this->assertSame('hostname', $value);

		$url = 'localhost.php';
		$value = $instance->getUrlDomain($url);
		$this->assertSame('', $value);
	}

    public function test_8200()
	{
        $instance = Xoops::getInstance();

		$value = $instance->templateTouch(1);
		$this->assertSame(true, $value);
	}

    public function test_8300()
	{
        $instance = Xoops::getInstance();

		$instance->templateClearModuleCache(1);
	}

    public function test_8400()
	{
        $instance = Xoops::getInstance();

		$instance->deprecated('message');
	}

    public function test_8500()
	{
        $instance = Xoops::getInstance();

		$instance->disableErrorReporting();
	}

}
