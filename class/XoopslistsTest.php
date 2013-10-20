<?php
require_once(dirname(__FILE__).'/../init.php');
 
class XoopslistsTest extends MY_UnitTestCase
{
    
    public function SetUp() {
    }
		
    public function test_100() {
		$value = XoopsLists::getTimeZoneList();
		foreach($value as $k => $v) {
			$this->assertTrue(is_numeric($k));
			$this->assertTrue(is_string($v));
		}
    }
	
    public function test_120() {
		$list_ref = XoopsLists::getDirListAsArray(XOOPS_THEME_PATH . '/');
		$value = XoopsLists::getThemesList();
        $this->assertSame($list_ref, $value);
		
		$list_ref = XoopsLists::getDirListAsArray(XOOPS_THEME_PATH );
		$value = XoopsLists::getThemesList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_140() {
		$list_ref = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/modules/');
		$value = XoopsLists::getModulesList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_160() {
		$list_ref = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/');
		$value = XoopsLists::getEditorList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_180() {
		$value = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/');
		$list_ref = XoopsLists::getEditorList();
        $this->assertSame($list_ref, $value);
	}
	
    public function test_200() {
		$value = XoopsLists::getFileListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
		$prefix='toto';
		$value = XoopsLists::getFileListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor/',$prefix);
        $this->assertSame(0, strncmp(array_shift($value),$prefix,strlen($prefix)));
	}
	
    public function test_220() {
		$value = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
		$prefix='toto';
		$value = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/images/',$prefix);
        $this->assertSame(0, strncmp(array_shift($value),$prefix,strlen($prefix)));
	}
	
    public function test_240() {
		$value = XoopsLists::getHtmlListAsArray(XOOPS_ROOT_PATH . '/themes/');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
		$prefix='toto';
		$value = XoopsLists::getHtmlListAsArray(XOOPS_ROOT_PATH . '/themes/',$prefix);
        $this->assertSame(0, strncmp(array_shift($value),$prefix,strlen($prefix)));
	}
	
    public function test_260() {
		$d_avatar = XOOPS_ROOT_PATH . '/images/avatar/';
		$is_dir = is_dir($d_avatar);
		if ($is_dir) {
			$value = XoopsLists::getAvatarsList();
			$this->assertTrue(is_array($value));
			$this->assertTrue(count($value)>0);
			$sdir = 'toto';
			$is_sdir = is_dir($d_avatar.$sdir);
			$value = XoopsLists::getAvatarsList($sdir);
			if ($is_sdir) {
				$this->assertTrue(is_array($value));
			} else {
				$this->assertSame(false, $value);
			}
		} else {
			$this->markTestSkipped('Directory not found : '.$d_avatar);
		}
	}
	
    public function test_280() {
		$value = XoopsLists::getAllAvatarsList();
		if ($value !== false) {
			$this->assertTrue(is_array($value));
			$this->assertTrue(count($value)>0);
		}
	}
	
    public function test_300() {
		$d_subject = XOOPS_ROOT_PATH . '/images/subject/';
		$is_dir = is_dir($d_subject);
		if ($is_dir) {
			$value = XoopsLists::getSubjectsList();
			$this->assertTrue(is_array($value));
			$this->assertTrue(count($value)>0);
			$sdir = 'toto';
			$is_sdir = is_dir($d_subject.$sdir);
			$value = XoopsLists::getSubjectsList($sdir);
			if ($is_sdir) {
				$this->assertTrue(is_array($value));
			} else {
			$this->markTestSkipped('Directory not found : '.$$d_subject.$sdir);
			}
		} else {
			$this->markTestSkipped('Directory not found : '.$d_subject);
		}
	}
	
    public function test_320() {
		$value = XoopsLists::getLangList();
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
	}
	
    public function test_330() {
		$value = XoopsLists::getLocaleList();
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value)>0);
	}
	
    public function test_340() {
		$value = XoopsLists::getCountryList();
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
	
    public function test_360() {
		$value = XoopsLists::getHtmlList();
        $this->assertTrue(is_array($value));
		foreach($value as $k => $v) {
			$this->assertRegExp('/^[a-z0-9]+$/',$k);
			$this->assertRegExp('/^(\&lt;)?[a-z0-9]+(\&gt;)?$/',$v);
		}
	}
	
    public function test_380() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new XoopsRanksHandler($db);
		$obj=new XoopsRanks();
		$obj->setDirty();
		$obj->setNew();
		$obj->setVar('rank_special',1);
        $value = $instance->insert($obj);
		$this->assertTrue(is_string($value));
		$value = XoopsLists::getUserRankList();
		$this->assertTrue(is_array($value));
		$this->assertTrue(count($value)>0);
	}
}
