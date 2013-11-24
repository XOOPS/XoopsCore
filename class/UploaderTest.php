<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UploaderTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsMediaUploader';
    
    public function SetUp() {
    }
    
    public function test_100() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
        $this->assertInstanceOf($this->myclass, $theme);
		$this->assertSame(false, $theme->allowUnknownTypes);
		$this->assertTrue(property_exists($theme, 'mediaName'));
		$this->assertTrue(property_exists($theme, 'mediaType'));	
		$this->assertTrue(property_exists($theme, 'mediaSize'));
		$this->assertTrue(property_exists($theme, 'mediaTmpName'));	
		$this->assertTrue(property_exists($theme, 'mediaError'));			
		$this->assertSame('', $theme->mediaRealType);
		$this->assertSame($upload_dir, $theme->uploadDir);
		$this->assertSame($allowed_mime_types, $theme->allowedMimeTypes);
		$this->assertSame(array('application/x-httpd-php'), $theme->deniedMimeTypes);
		$this->assertSame(0, $theme->maxFileSize);
		$this->assertTrue(property_exists($theme, 'maxWidth'));
		$this->assertTrue(property_exists($theme, 'maxHeight'));
		$this->assertTrue(property_exists($theme, 'targetFileName'));
		$this->assertTrue(property_exists($theme, 'prefix'));
		$this->assertSame(array(), $theme->errors);
		$this->assertTrue(property_exists($theme, 'savedDestination'));
		$this->assertTrue(property_exists($theme, 'savedFileName'));
		$this->assertTrue(is_array($theme->extensionToMime));
		$this->assertSame(true, $theme->checkImageType);
		$extensionsToBeSanitized = array(
			'php', 'phtml', 'phtm', 'php3', 'php4', 'cgi', 'pl', 'asp', 'php5'
		);
		$this->assertSame($extensionsToBeSanitized, $theme->extensionsToBeSanitized);
		$imageExtensions = array(
			1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'swf', 5 => 'psd', 6 => 'bmp', 7 => 'tif', 8 => 'tif', 9 => 'jpc',
			10 => 'jp2', 11 => 'jpx', 12 => 'jb2', 13 => 'swf', 14 => 'iff', 15 => 'wbmp', 16 => 'xbm'
		);
		$this->assertSame($imageExtensions, $theme->imageExtensions);
    }
	
    public function test_150() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$maxFileSize = 71;
		$maxWidth = 72;
		$maxHeight = 73;
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types,$maxFileSize,$maxWidth,$maxHeight);
        $this->assertInstanceOf($this->myclass, $theme);
		$this->assertSame($maxFileSize, $theme->maxFileSize);
		$this->assertSame($maxWidth, $theme->maxWidth);
		$this->assertSame($maxHeight, $theme->maxHeight);
    }
	
    public function test_200() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// fetchMedia
		$this->markTestSkipped('');
	}
	
    public function test_250() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$nom = 'toto';
		$theme->setTargetFileName('  '.$nom.'  ');
		$this->assertSame($nom, $theme->targetFileName);
	}
	
    public function test_300() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$nom = 'toto';
		$theme->setPrefix('  '.$nom.'  ');
		$this->assertSame($nom, $theme->prefix);
	}
	
    public function test_350() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$name = 'titi';
		$theme->mediaName = $name;
		$value = $theme->getMediaName();
		$this->assertSame($name, $value);
	}
	
    public function test_400() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$name = 'titi';
		$theme->mediaType = $name;
		$value = $theme->getMediaType();
		$this->assertSame($name, $value);
	}
	
    public function test_450() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$size = 71;
		$theme->mediaSize = $size;
		$value = $theme->getMediaSize();
		$this->assertSame($size, $value);
	}
	
    public function test_500() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$name = 'titi';
		$theme->mediaTmpName = $name;
		$value = $theme->getMediaTmpName();
		$this->assertSame($name, $value);
	}
	
    public function test_550() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$name = 'titi';
		$theme->savedFileName = $name;
		$value = $theme->getSavedFileName();
		$this->assertSame($name, $value);
	}
	
    public function test_600() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		$name = 'titi';
		$theme->savedDestination = $name;
		$value = $theme->getSavedDestination();
		$this->assertSame($name, $value);
	}
	
    public function test_650() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// upload
		$this->markTestSkipped('');
	}
	
    public function test_660() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// _copyFile
		$this->markTestSkipped('');
	}
	
    public function test_700() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// checkMaxFileSize
		$this->markTestSkipped('');
	}
	
    public function test_750() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// checkMaxWidth
		$this->markTestSkipped('');
	}
	
    public function test_800() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// checkMaxHeight
		$this->markTestSkipped('');
	}
	
    public function test_850() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// checkMimeType
		$this->markTestSkipped('');
	}
	
    public function test_900() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// checkImageType
		$this->markTestSkipped('');
	}
	
    public function test_950() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// sanitizeMultipleExtensions
		$this->markTestSkipped('');
	}
	
    public function test_1000() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// setErrors
		$this->markTestSkipped('');
	}
	
    public function test_1050() {
		$upload_dir = 'upload_dir';
		$allowed_mime_types = array('toto');
		$theme = new  $this->myclass($upload_dir, $allowed_mime_types);
		// getErrors
		$this->markTestSkipped('');
	}
	
}
