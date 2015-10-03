<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModuleMyTextSanitizerTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'MyTextSanitizer';

    public function test_getInstance()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $this->assertInstanceOf($this->myClass, $sanitizer);
        $xoops_root_path = XoopsBaseConfig::get('root-path');
        $this->assertEquals($xoops_root_path . '/class/textsanitizer', $sanitizer->path_basic);
        $this->assertEquals($xoops_root_path . '/Frameworks/textsanitizer', $sanitizer->path_plugin);
    }

    public function test_getinstance100()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $this->assertInstanceOf($this->myClass, $sanitizer);
        $sanitizer2 = $class::getInstance();
        $this->assertSame($sanitizer2, $sanitizer);
    }

    public function test_loadConfig()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $config = $sanitizer->loadConfig();
        $this->assertTrue(is_array($config));
    }

    public function test_mergeConfig()
	{
		$class = $this->myClass;
        $array1 = array('x' => 'toto');
        $array2 = array('y' => array('yy' => 'titi'));
        $sanitizer = $class::getInstance();
        $config = $sanitizer->mergeConfig($array1,$array2);
        $this->assertTrue(is_array($config));
        $this->assertEquals(count($array1)+count($array2), count($config));
        $this->assertEquals($array1['x'], $config['x']);
        $this->assertEquals($array2['y']['yy'], $config['y']['yy']);
    }

    public function test_getSmileys()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $smileys = $sanitizer->getSmileys();
        $this->assertTrue(is_array($smileys));
    }

    public function test_smiley()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $message = $sanitizer->smiley('happy :-) happy');
        $this->assertTrue(is_string($message));
    }

    public function test_makeClickable()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = 'toto';
        $message = $sanitizer->makeClickable($text);
        $this->assertTrue(is_string($text));
    }

    public function test_truncate()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $sanitizer->config['truncate_length'] = 12;
        $text = 'toto titi bidon tutu tata';
        $message = $class::truncate($text);
        $this->assertEquals('toto ... tata', $message);
    }

    public function test_truncate100()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $sanitizer->config['truncate_length'] = 4;
        $text = 'toto titi tutu tata';
        $message = $class::truncate($text);
        $this->assertEquals(substr($text,0,4), $message);
    }
    
    /**
    * callback for test
    */
    public function decode_check_level($sanitizer, $text)
    {
        $level = ob_get_level();
        $message = $sanitizer->xoopsCodeDecode($text);
        while (ob_get_level() > $level) @ob_end_flush();
        return $message;
    }
    
    public function test_xoopsCodeDecode()
	{
        $path = \XoopsBaseConfig::get('root-path');
        if (! class_exists('Comments', false)) {
            \XoopsLoad::addMap(array(
                'comments'          => $path . '/modules/comments/class/helper.php',
            ));
        }
        if (! class_exists('MenusDecorator', false)) {
            \XoopsLoad::addMap(array(
                'menusdecorator'    => $path . '/modules/menus/class/decorator.php',
            ));
        }
        if (! class_exists('MenusBuilder', false)) {
            \XoopsLoad::addMap(array(
                'menusbuilder'    => $path . '/modules/menus/class/builder.php',
            ));
        }
        
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
		$host = 'monhost.fr';
		$site = 'MonSite';

        $text = '[siteurl="'.$host.'"]'.$site.'[/siteurl]';
        $message = $this->decode_check_level($sanitizer, $text);
        $xoops_url = \XoopsBaseConfig::get('url');
        $this->assertEquals('<a href="'.$xoops_url.'/'.$host.'" title="">'.$site.'</a>', $message);
        $text = '[siteurl=\''.$host.'\']'.$site.'[/siteurl]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="'.$xoops_url.'/'.$host.'" title="">'.$site.'</a>', $message);

        $text = '[url="http://'.$host.'"]'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'http://'.$host.'\']'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="https://'.$host.'"]'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="https://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'https://'.$host.'\']'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="https://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="ftp://'.$host.'"]'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="ftp://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'ftp://'.$host.'\']'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="ftp://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="ftps://'.$host.'"]'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="ftps://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'ftps://'.$host.'\']'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="ftps://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="'.$host.'"]'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\''.$host.'\']'.$site.'[/url]';
        $message = $this->decode_check_level($sanitizer, $text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
    }

    public function test_xoopsCodeDecode100()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
		$string = 'string';

		$color = 'color';
        $text = '[color="'.$color.'"]'.$string.'[/color]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<span style="color: #'.$color.';">'.$string.'</span>',$message);
        $text = '[color=\''.$color.'\']'.$string.'[/color]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<span style="color: #'.$color.';">'.$string.'</span>',$message);

		$size = 'size-size';
        $text = '[size="'.$size.'"]'.$string.'[/size]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<span style="font-size: '.$size.';">'.$string.'</span>',$message);
        $text = '[size=\''.$size.'\']'.$string.'[/size]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<span style="font-size: '.$size.';">'.$string.'</span>',$message);

		$font = 'font-font';
        $text = '[font="'.$font.'"]'.$string.'[/font]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<span style="font-family: '.$font.';">'.$string.'</span>',$message);
        $text = '[font=\''.$font.'\']'.$string.'[/font]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<span style="font-family: '.$font.';">'.$string.'</span>',$message);
	}

    public function test_xoopsCodeDecode200()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
		$string = 'string';

        $text = '[b]'.$string.'[/b]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<strong>'.$string.'</strong>',$message);
        $text = '[i]'.$string.'[/i]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<em>'.$string.'</em>',$message);
        $text = '[u]'.$string.'[/u]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<u>'.$string.'</u>',$message);
        $text = '[d]'.$string.'[/d]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<del>'.$string.'</del>',$message);
        $text = '[center]'.$string.'[/center]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<div style="text-align: center;">'.$string.'</div>',$message);
        $text = '[left]'.$string.'[/left]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<div style="text-align: left;">'.$string.'</div>',$message);
        $text = '[right]'.$string.'[/right]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<div style="text-align: right;">'.$string.'</div>',$message);
	}

    public function test_quoteConv()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
		$string = 'string';
        $text = '[quote]'.$string.'[/quote]';
        $message = $sanitizer->quoteConv($text);
        $this->assertEquals(XoopsLocale::C_QUOTE . '<div class="xoopsQuote"><blockquote>'.$string.'</blockquote></div>',$message);

		$string = 'string';
        $text = '[quote]toto'.'[quote]'.$string.'[/quote]'.'titi[/quote]';
        $message = $sanitizer->quoteConv($text);
        $this->assertEquals(XoopsLocale::C_QUOTE . '<div class="xoopsQuote"><blockquote>totoQuote:<div class="xoopsQuote"><blockquote>'.$string.'</blockquote></div>titi</blockquote></div>',$message);

    }

    public function test_filterxss()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = "\x00";
        $message = $sanitizer->filterxss($text);
        $this->assertEquals('',$message);
    }

    public function test_nl2br()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = "\n";
        $message = $sanitizer->nl2br($text);
        $this->assertEquals('<br />',$message);
        $text = "\r\n";
        $message = $sanitizer->nl2br($text);
        $this->assertEquals('<br />',$message);
        $text = "\r";
        $message = $sanitizer->nl2br($text);
        $this->assertEquals('<br />',$message);
    }

    public function test_addSlashes()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = 'toto titi \'tutu tata';
        $text2 = $text;
        if (!get_magic_quotes_gpc()) {
            $text2 = addslashes($text);
        }
        $message = $sanitizer->addSlashes($text);
        $this->assertEquals($text2,$message);
    }

    public function test_stripSlashesGPC()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = 'toto titi \\\'tutu tata';
        $text2 = $text;
        if (get_magic_quotes_gpc()) {
            $text2 = stripslashes($text);
        }
        $message = $sanitizer->stripSlashesGPC($text);
        $this->assertEquals($text2,$message);
    }

    public function test_htmlSpecialChars()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = "\"'<>&";
        $message = $sanitizer->htmlSpecialChars($text);
        $this->assertSame('&quot;&#039;&lt;&gt;&',$message);

        $text = 'toto&titi';
        $message = $sanitizer->htmlSpecialChars($text);
        $this->assertSame('toto&titi',$message);

        $text = 'toto&nbsp;titi';
        $message = $sanitizer->htmlSpecialChars($text);
        $this->assertSame('toto&amp;nbsp;titi',$message);
    }

    public function test_undohtmlSpecialChars()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = '&gt;&lt;&quot;&#039;&amp;nbsp;';
        $message = $sanitizer->undohtmlSpecialChars($text);
        $this->assertSame('><"\'&nbsp;',$message);
    }

    public function test_displayTarea()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
		$text = 'éeidoà';
        $message = $sanitizer->displayTarea($text,1);
        $this->assertSame($text, $message);
    }

    public function test_previewTarea()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        //$sanitizer->previewTarea();
		$this->markTestSkipped('');
    }

    public function test_censorString()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        //$sanitizer->censorString();
		$this->markTestSkipped('');
    }

    public function test_codePreConv()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = '[codephp]tototiti[/code]';
        $message = $sanitizer->codePreConv($text);
        $this->assertSame('[codephp]dG90b3RpdGk=[/code]',$message);
        $message = $sanitizer->codePreConv($text,0);
        $this->assertSame($text,$message);
    }

    public function test_codeConv()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = '[codephp]tototiti[/code]';
        $message = $sanitizer->codeConv($text);
        $result = preg_match('/^\<div class=\\"xoopsCode\\"\>/',$message);
        $this->assertSame($result,0);
    }

    public function test_executeExtensions()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $value = $sanitizer->executeExtensions();
        $this->assertTrue($value);
    }

    public function test_loadExtension()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $value = $sanitizer->loadExtension('toto');
        $this->assertFalse($value);
    }

    public function test_executeExtension()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        //$sanitizer->executeExtension();
		$this->markTestSkipped('');
    }

    public function test_textFilter()
	{
		$class = $this->myClass;
        $sanitizer = $class::getInstance();
        $text = 'toto titi tutu tata';
		PHPUnit_Framework_Error_Warning::$enabled = FALSE;
        $value = $sanitizer->textFilter($text);
        $this->assertSame($text, $value);
    }
}
