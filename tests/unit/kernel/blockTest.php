<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BlockTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp()
	{
    }
    
    public function test___construct()
	{
        $instance = new XoopsBlock();
        $this->assertInstanceOf('XoopsBlock',$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['bid']));
        $this->assertTrue(isset($value['mid']));
        $this->assertTrue(isset($value['func_num']));
        $this->assertTrue(isset($value['options']));
        $this->assertTrue(isset($value['name']));
        $this->assertTrue(isset($value['title']));
        $this->assertTrue(isset($value['content']));
        $this->assertTrue(isset($value['side']));
        $this->assertTrue(isset($value['weight']));
        $this->assertTrue(isset($value['visible']));
        $this->assertTrue(isset($value['block_type']));
        $this->assertTrue(isset($value['c_type']));
        $this->assertTrue(isset($value['isactive']));
        $this->assertTrue(isset($value['dirname']));
        $this->assertTrue(isset($value['func_file']));
        $this->assertTrue(isset($value['show_func']));
        $this->assertTrue(isset($value['edit_func']));
        $this->assertTrue(isset($value['template']));
        $this->assertTrue(isset($value['bcachetime']));
        $this->assertTrue(isset($value['last_modified']));
    }
	
    public function test___construct100()
	{
        $instance = new XoopsBlock(1);
        $this->assertInstanceOf('XoopsBlock',$instance);
	}
    
    public function test_id()
	{
        $instance = new XoopsBlock();
        $value = $instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_bid()
	{
        $instance = new XoopsBlock();
        $value = $instance->bid();
        $this->assertSame(null,$value);
    }
    
    public function test_mid()
	{
        $instance = new XoopsBlock();
        $value = $instance->mid();
        $this->assertSame(0,$value);
    }
    
    public function test_func_num()
	{
        $instance = new XoopsBlock();
        $value = $instance->func_num();
        $this->assertSame(0,$value);
    }
    
    public function test_options()
	{
        $instance = new XoopsBlock();
        $value = $instance->options();
        $this->assertSame(null,$value);
    }
    
    public function test_name()
	{
        $instance = new XoopsBlock();
        $value = $instance->name();
        $this->assertSame(null,$value);
    }
    
    public function test_title()
	{
        $instance = new XoopsBlock();
        $value = $instance->title();
        $this->assertSame(null,$value);
    }
    
    public function test_content()
	{
        $instance = new XoopsBlock();
        $value = $instance->content();
        $this->assertSame(null,$value);
    }
    
    public function test_side()
	{
        $instance = new XoopsBlock();
        $value = $instance->side();
        $this->assertSame(0,$value);
    }

    public function test_weight()
	{
        $instance = new XoopsBlock();
        $value = $instance->weight();
        $this->assertSame(0,$value);
    }
    
    public function test_visible()
	{
        $instance = new XoopsBlock();
        $value = $instance->visible();
        $this->assertSame(0,$value);
    }
    
    public function test_block_type()
	{
        $instance = new XoopsBlock();
        $value = $instance->block_type();
        $this->assertSame(null,$value);
    }
    
    public function test_c_type()
	{
        $instance = new XoopsBlock();
        $value = $instance->c_type();
        $this->assertSame(null,$value);
    }
    
    public function test_isactive()
	{
        $instance = new XoopsBlock();
        $value = $instance->isactive();
        $this->assertSame(null,$value);
    }
    
    public function test_dirname()
	{
        $instance = new XoopsBlock();
        $value = $instance->dirname();
        $this->assertSame(null,$value);
    }
    
    public function test_func_file() {
        $instance=new XoopsBlock();
        $value = $instance->func_file();
        $this->assertSame(null,$value);
    }
    
    public function test_show_func()
	{
        $instance = new XoopsBlock();
        $value = $instance->show_func();
        $this->assertSame(null,$value);
    }
    
    public function test_edit_func()
	{
        $instance = new XoopsBlock();
        $value = $instance->edit_func();
        $this->assertSame(null,$value);
    }

    public function test_template()
	{
        $instance = new XoopsBlock();
        $value = $instance->template();
        $this->assertSame($value,null);
    }
    
    public function test_bcachetime()
	{
        $instance = new XoopsBlock();
        $value = $instance->bcachetime();
        $this->assertSame(0,$value);
    }
    
    public function test_last_modified()
	{
        $instance = new XoopsBlock();
        $value = $instance->last_modified();
        $this->assertSame(0,$value);
    }
    
    public function test_getContent()
	{
        $instance = new XoopsBlock();
        $level = ob_get_level();
        $value = $instance->getContent();
        while(ob_get_level() > $level) ob_end_clean();
        $this->assertSame('',$value);
        $value = $instance->getContent('s','T');
        $this->assertSame('',$value);
        $value = $instance->getContent('s','H');
        $this->assertSame('',$value);
        $value = $instance->getContent('s','P');
        $this->assertSame('',$value);
        $value = $instance->getContent('s','S');
        $this->assertSame('',$value);
        $value = $instance->getContent('e');
        $this->assertSame('',$value);
        $value = $instance->getContent('default');
        $this->assertSame(null,$value);
    }
    
    public function test_getOptions()
	{
        $instance = new XoopsBlock();
        $value = $instance->getOptions();
        $this->assertSame(false,$value);

        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		require_once $xoops_root_path.'/modules/page/locale/en_US/en_US.php';		
		require_once $xoops_root_path.'/modules/page/locale/en_US/locale.php';
		
		$instance->setVar('dirname', 'page');
		$instance->setVar('func_file', 'page_blocks.php');
		$instance->setVar('edit_func', 'page_blocks_edit');
		$instance->setVar('options', 'a|b|c|d|e');
        $value = $instance->getOptions();
        $this->assertTrue(is_string($value));
		
		$instance->setVar('dirname', 'page');
		$instance->setVar('func_file', 'page_blocks.php');
		$instance->setVar('edit_func', 'function_not_exists');
		$instance->setVar('options', 'a|b|c|d|e');
        $value = $instance->getOptions();
        $this->assertSame(false, $value);
		
		$instance->setVar('dirname', 'page');
		$instance->setVar('func_file', 'file_not_found.php');
		$instance->setVar('edit_func', 'page_blocks_edit');
		$instance->setVar('options', 'a|b|c|d|e');
        $value = $instance->getOptions();
        $this->assertSame(false, $value);
    }
    
    public function test_isCustom()
	{
        $instance = new XoopsBlock();
        $value = $instance->isCustom();
        $this->assertFalse($value);
		
		$instance->setVar('block_type','C');
        $value = $instance->isCustom();
        $this->assertTrue($value);
		
		$instance->setVar('block_type','E');
        $value = $instance->isCustom();
        $this->assertTrue($value);
    }
    
    public function test_buildBlock()
	{
        $instance = new XoopsBlock();
        $value = $instance->buildBlock();
        $this->assertSame(false,$value);
		
		$instance->setVar('block_type','');
        $value = $instance->isCustom();
        $this->assertFalse($value);
        $value = $instance->buildBlock();
        $this->assertSame(false,$value);
		
		$instance->setVar('block_type','C');
        $value = $instance->isCustom();
        $this->assertTrue($value);	
		
		$instance->setVar('dirname', 'page');
		$instance->setVar('func_file', 'page_blocks.php');
		$instance->setVar('show_func', 'page_blocks_show');
		$instance->setVar('options', 'a|b|c|d|e');
        $value = $instance->buildBlock();
        $this->assertSame(false, $value);
		
		$instance->setVar('dirname', 'page');
		$instance->setVar('func_file', 'page_blocks.php');
		$instance->setVar('show_func', 'function_not_exists');
		$instance->setVar('options', 'a|b|c|d|e');
        $value = $instance->buildBlock();
        $this->assertSame(false, $value);
		
		$instance->setVar('dirname', 'page');
		$instance->setVar('func_file', 'file_not_found.php');
		$instance->setVar('show_func', 'page_blocks_show');
		$instance->setVar('options', 'a|b|c|d|e');
        $value = $instance->buildBlock();
        $this->assertSame(false, $value);
    }
}

