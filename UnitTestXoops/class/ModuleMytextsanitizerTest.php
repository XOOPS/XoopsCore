<?php
require_once(dirname(__FILE__).'/../init.php');
 
class ModuleMyTextSanitizerTest extends MY_UnitTestCase
{
    protected $myclass = 'MyTextSanitizer';
    
    public function test_100() {
        $sanitizer = MyTextSanitizer::getInstance();
        $this->assertInstanceOf($this->myclass, $sanitizer);
        $this->assertEquals(XOOPS_ROOT_PATH . '/class/textsanitizer', $sanitizer->path_basic);        
        $this->assertEquals(XOOPS_ROOT_PATH . '/Frameworks/textsanitizer', $sanitizer->path_plugin);        
    }
    
    public function test_120() {
        $sanitizer = MyTextSanitizer::getInstance();
        $config = $sanitizer->loadConfig();
        $this->assertTrue(is_array($config));
    }
    
    public function test_140() {
        $array1 = array('x' => 'toto');
        $array2 = array('y' => array('yy' => 'titi'));
        $sanitizer = MyTextSanitizer::getInstance();
        $config = $sanitizer->mergeConfig($array1,$array2);
        $this->assertTrue(is_array($config));
        $this->assertEquals(count($array1)+count($array2), count($config));
        $this->assertEquals($array1['x'], $config['x']);
        $this->assertEquals($array2['y']['yy'], $config['y']['yy']);
    }
    
    public function test_160() {
        $sanitizer = MyTextSanitizer::getInstance();
        $this->assertInstanceOf($this->myclass, $sanitizer);
    }
    
    public function test_180() {
        $sanitizer = MyTextSanitizer::getInstance();
        $this->assertInstanceOf($this->myclass, $sanitizer);
        $sanitizer2 = MyTextSanitizer::getInstance();
        $this->assertSame($sanitizer2, $sanitizer);
    }
    
    public function test_200() {
        $sanitizer = MyTextSanitizer::getInstance();
        $smileys = $sanitizer->getSmileys();
        $this->assertTrue(is_array($smileys));
    }
    
    public function test_220() {
        $sanitizer = MyTextSanitizer::getInstance();
        $message = $sanitizer->smiley('happy :-) happy');
        $this->assertTrue(is_string($message));
    }
    
    public function test_240() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = 'toto';
        $message = $sanitizer->makeClickable($text);
        $this->assertTrue(is_string($text));
    }
    
    public function test_260() {
        $sanitizer = MyTextSanitizer::getInstance();
        $sanitizer->config['truncate_length'] = 12;
        $text = 'toto titi bidon tutu tata';
        $message = MyTextSanitizer::truncate($text);
        $this->assertEquals('toto ... tata', $message);
    }
    
    public function test_280() {
        $sanitizer = MyTextSanitizer::getInstance();
        $sanitizer->config['truncate_length'] = 4;
        $text = 'toto titi tutu tata';
        $message = MyTextSanitizer::truncate($text);
        $this->assertEquals(substr($text,0,4), $message);
    }
    
    public function test_300() {
        $sanitizer = MyTextSanitizer::getInstance();
		$host = 'monhost.fr';
		$site = 'MonSite';

        $text = '[siteurl="'.$host.'"]'.$site.'[/siteurl]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="'.XOOPS_URL.'/'.$host.'" title="">'.$site.'</a>', $message);
        $text = '[siteurl=\''.$host.'\']'.$site.'[/siteurl]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="'.XOOPS_URL.'/'.$host.'" title="">'.$site.'</a>', $message);
		
        $text = '[url="http://'.$host.'"]'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'http://'.$host.'\']'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="https://'.$host.'"]'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="https://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'https://'.$host.'\']'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="https://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="ftp://'.$host.'"]'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="ftp://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'ftp://'.$host.'\']'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="ftp://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="ftps://'.$host.'"]'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="ftps://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\'ftps://'.$host.'\']'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="ftps://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url="'.$host.'"]'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
        $text = '[url=\''.$host.'\']'.$site.'[/url]';
        $message = $sanitizer->xoopsCodeDecode($text);
        $this->assertEquals('<a href="http://'.$host.'" rel="external" title="">'.$site.'</a>', $message);
    }
	
    public function test_305() {
        $sanitizer = MyTextSanitizer::getInstance();
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
	
    public function test_310() {
        $sanitizer = MyTextSanitizer::getInstance();
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
    
    public function test_320() {
        $sanitizer = MyTextSanitizer::getInstance();
		$string = 'string';
        $text = '[quote]'.$string.'[/quote]';
        $message = $sanitizer->quoteConv($text);
        $this->assertEquals(XoopsLocale::C_QUOTE . '<div class="xoopsQuote"><blockquote>'.$string.'</blockquote></div>',$message);
		
		$string = 'string';
        $text = '[quote]toto'.'[quote]'.$string.'[/quote]'.'titi[/quote]';
        $message = $sanitizer->quoteConv($text);
        $this->assertEquals(XoopsLocale::C_QUOTE . '<div class="xoopsQuote"><blockquote>totoQuote:<div class="xoopsQuote"><blockquote>'.$string.'</blockquote></div>titi</blockquote></div>',$message);	

    }
    
    public function test_340() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = "\x00";
        $message = $sanitizer->filterxss($text);
        $this->assertEquals('',$message);
    }
    
    public function test_360() {
        $sanitizer = MyTextSanitizer::getInstance();
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
    
    public function test_380() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = 'toto titi \'tutu tata';
        $text2 = $text;
        if (!get_magic_quotes_gpc()) {
            $text2 = addslashes($text);
        }        
        $message = $sanitizer->addSlashes($text);
        $this->assertEquals($text2,$message);
    }
    
    public function test_400() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = 'toto titi \\\'tutu tata';
        $text2 = $text;
        if (get_magic_quotes_gpc()) {
            $text2 = stripslashes($text);
        }
        $message = $sanitizer->stripSlashesGPC($text);
        $this->assertEquals($text2,$message);
    }
    
    public function test_420() {
        $sanitizer = MyTextSanitizer::getInstance();
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
    
    public function test_440() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = '&gt;&lt;&quot;&#039;&amp;nbsp;';
        $message = $sanitizer->undohtmlSpecialChars($text);
        $this->assertSame('><"\'&nbsp;',$message);
    }
    
    public function test_460() {
        $sanitizer = MyTextSanitizer::getInstance();
		$text = 'éeidoà';
        $message = $sanitizer->displayTarea($text,1);
        $this->assertSame($text, $message);
    }
    
    public function test_480() {
        $sanitizer = MyTextSanitizer::getInstance();
        //$sanitizer->previewTarea();
		$this->markTestSkipped('');
    }

    public function test_500() {
        $sanitizer = MyTextSanitizer::getInstance();
        //$sanitizer->censorString();
		$this->markTestSkipped('');
    }
    
    public function test_520() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = '[codephp]tototiti[/code]';
        $message = $sanitizer->codePreConv($text);
        $this->assertSame('[codephp]dG90b3RpdGk=[/code]',$message);
        $message = $sanitizer->codePreConv($text,0);
        $this->assertSame($text,$message);
    }
    
    public function test_540() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = '[codephp]tototiti[/code]';
        $message = $sanitizer->codeConv($text);
        $this->assertSame("<div class=\"xoopsCode\"><code><span style=\"color: #000000\">\n<span style=\"color: #0000BB\">¶‹h¶</span><span style=\"color: #007700\">+</span><span style=\"color: #0000BB\">b</span>\n</span>\n</code></div>",$message);
    }
    
    public function test_560() {
        $sanitizer = MyTextSanitizer::getInstance();
        $value = $sanitizer->executeExtensions();
        $this->assertTrue($value);
    }
    
    public function test_580() {
        $sanitizer = MyTextSanitizer::getInstance();
        $value = $sanitizer->loadExtension('toto');
        $this->assertFalse($value);
    }
    
    public function test_600() {
        $sanitizer = MyTextSanitizer::getInstance();
        //$sanitizer->executeExtension();
		$this->markTestSkipped('');
    }
    
    public function test_620() {
        $sanitizer = MyTextSanitizer::getInstance();
        $text = 'toto titi tutu tata';
        $value = $sanitizer->textFilter($text);
        $this->assertSame($text, $value);
    }
}
