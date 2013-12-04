<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopslistsTest extends MY_UnitTestCase
{
	protected $myClass = 'XoopsLists';
	protected $conn = null;
    
    public function SetUp()
	{
		if (empty($this->conn)) {
			$this->conn = Xoops::getInstance()->db();
		}
    }
		
    public function test_getTimeZoneList()
	{
		$class = $this->myClass;
		$value = $class::getTimeZoneList();
		foreach($value as $k => $v) {
			$this->assertTrue(is_numeric($k));
			$this->assertTrue(is_string($v));
		}
    }
	
    public function test_getDirListAsArray()
	{
	}
	
	public function test_getThemesList()
	{
		$class = $this->myClass;
		$list_ref = $class::getDirListAsArray(XOOPS_THEME_PATH . '/');
		$value = $class::getThemesList();
        $this->assertSame($list_ref, $value);
		
		$list_ref = $class::getDirListAsArray(XOOPS_THEME_PATH );
		$value = $class::getThemesList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_getModulesList()
	{
		$class = $this->myClass;
		$list_ref = $class::getDirListAsArray(XOOPS_ROOT_PATH . '/modules/');
		$value = $class::getModulesList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_getEditorList()
	{
		$class = $this->myClass;
		$list_ref = $class::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/');
		$value = $class::getEditorList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_getFileListAsArray()
	{
		$class = $this->myClass;
		$value = $class::getFileListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
		$prefix='toto';
		$value = $class::getFileListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/',$prefix);
        $this->assertSame(0, strncmp(array_shift($value),$prefix,strlen($prefix)));
	}
	
    public function test_getImgListAsArray()
	{
		$class = $this->myClass;
		$value = $class::getImgListAsArray(XOOPS_ROOT_PATH . '/images/');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
		$prefix='toto';
		$value = $class::getImgListAsArray(XOOPS_ROOT_PATH . '/images/',$prefix);
        $this->assertSame(0, strncmp(array_shift($value),$prefix,strlen($prefix)));
	}
	
    public function test_getHtmlListAsArray()
	{
		$class = $this->myClass;
		$value = $class::getHtmlListAsArray(XOOPS_ROOT_PATH . '/themes/');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
		$prefix='toto';
		$value = $class::getHtmlListAsArray(XOOPS_ROOT_PATH . '/themes/',$prefix);
        $this->assertSame(0, strncmp(array_shift($value),$prefix,strlen($prefix)));
	}
	
    public function test_getAvatarsList()
	{
		$class = $this->myClass;
		$d_avatar = XOOPS_ROOT_PATH . '/images/avatar/';
		$is_dir = is_dir($d_avatar);
		if ($is_dir) {
			$value = $class::getAvatarsList();
			$this->assertTrue(is_array($value));
			$this->assertTrue(count($value)>0);
			$sdir = 'toto';
			$is_sdir = is_dir($d_avatar.$sdir);
			$value = $class::getAvatarsList($sdir);
			if ($is_sdir) {
				$this->assertTrue(is_array($value));
			} else {
				$this->assertSame(false, $value);
			}
		} else {
			$this->markTestSkipped('Directory not found : '.$d_avatar);
		}
	}
	
    public function test_getAllAvatarsList()
	{
		$class = $this->myClass;
		$value = $class::getAllAvatarsList();
		if ($value !== false) {
			$this->assertTrue(is_array($value));
			$this->assertTrue(count($value)>0);
		}
	}
	
    public function test_getSubjectsList()
	{
		$class = $this->myClass;
		$d_subject = XOOPS_ROOT_PATH . '/images/subject/';
		$is_dir = is_dir($d_subject);
		if ($is_dir) {
			$value = $class::getSubjectsList();
			$this->assertTrue(is_array($value));
			$this->assertTrue(count($value)>0);
			$sdir = 'toto';
			$is_sdir = is_dir($d_subject.$sdir);
			$value = $class::getSubjectsList($sdir);
			if ($is_sdir) {
				$this->assertTrue(is_array($value));
			} else {
			$this->markTestSkipped('Directory not found : '.$d_subject.$sdir);
			}
		} else {
			$this->markTestSkipped('Directory not found : '.$d_subject);
		}
	}
	
    public function test_getLangList()
	{
		$class = $this->myClass;
		$value = $class::getLangList();
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
	}
	
    public function test_getLocaleList()
	{
		$class = $this->myClass;
		$value = $class::getLocaleList();
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
	}
	
    public function test_340()
	{
		$class = $this->myClass;
		$value = $class::getCountryList();
        $this->assertTrue(is_array($value));
		foreach($value as $k => $v) {
			if (empty($k)) {
				$this->assertSame('-', $v);			
			} else {
				$this->assertRegExp('/^[A-Z][A-Z]$/',$k);
				$this->assertTrue(is_string($v));
			}
		}
	}
	
    public function test_getHtmlList()
	{
		$class = $this->myClass;
		$value = $class::getHtmlList();
        $this->assertTrue(is_array($value));
		foreach($value as $k => $v) {
			$this->assertRegExp('/^[a-z0-9]+$/',$k);
			$this->assertRegExp('/^(\&lt;)?[a-z0-9]+(\&gt;)?$/',$v);
		}
	}
	
    public function test_getUserRankList()
	{
		$class = $this->myClass;
        $instance=new XoopsRanksHandler($this->conn);
		$obj=new XoopsRanks();
		$obj->setDirty();
		$obj->setNew();
		$obj->setVar('rank_special',1);
        $value = $instance->insert($obj);
		$this->markTestSkipped('');
		$this->assertTrue(is_string($value));
		$value = $class::getUserRankList();
		$this->assertTrue(is_array($value));
		$this->assertTrue(count($value)>0);
	}
	
}
