<?php
require_once(dirname(__FILE__).'/../../../init.php');

$xoops_root_path = \XoopsBaseConfig::get('root-path');
require_once($xoops_root_path.'/class/textsanitizer/censor/censor.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MytsCensorTest extends \PHPUnit_Framework_TestCase
{
	protected $myclass = 'MytsCensor';
	
    public function test___construct()
	{
		$ts = new MyTextSanitizer();
		$instance = new $this->myclass($ts);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('MyTextSanitizerExtension', $instance);
    }

    function test_load()
    {
		$ts = new MyTextSanitizer();
		$instance = new $this->myclass($ts);
		$this->assertInstanceOf($this->myclass, $instance);
		
		$censorConf = null;
        $xoops = Xoops::getInstance();
        if (!isset($censorConf)) {
            $censorConf = $xoops->getConfigs();
            $config = $ts->loadConfig(dirname(__FILE__));
            //merge and allow config override
            $censorConf = array_merge($censorConf, $config);
        }
		
		$censorConf['censor_enable'] = 0;
		$text = 'text';
		$value = $instance->load($ts, $text, $censorConf);
		$this->assertSame($text, $value);
		
		$censorConf['censor_enable'] = 1;
		$censorConf['censor_words'] = null;
		$text = 'text';
		$value = $instance->load($ts, $text, $censorConf);
		$this->assertSame($text, $value);
		
		$censorConf['censor_enable'] = 1;
		$censorConf['censor_words'] = array('shit','fuck');
		$text = 'text';
		$value = $instance->load($ts, $text, $censorConf);
		$this->assertSame($text, $value);
		
		$censorConf['censor_enable'] = 1;
		$censorConf['censor_words'] = array('shit','fuck');
		$censorConf['censor_admin'] = 1;
		$text = 'text';
		$value = $instance->load($ts, $text, $censorConf);
		$this->assertSame($text, $value);
		
		$censorConf['censor_enable'] = 1;
		$censorConf['censor_words'] = array('shit','fuck');
		$censorConf['censor_admin'] = 0;
		$replacement = empty($censorConf['censor_replace']) ? '##censor##' : $censorConf['censor_replace'];
		$text = 'text with fuck and shit and fuck';
		$value = $instance->load($ts, $text, $censorConf);
		$this->assertTrue(strpos($value, 'fuck') === false);
		$this->assertTrue(strpos($value, 'shit') === false);
		$this->assertTrue(strpos($value, $replacement) !== false);
    }
}