<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UploaderTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsMediaUploader';

    public function SetUp()
    {
    }

    public function test___construct()
    {
        $upload_dir = 'upload_dir';
        $allowed_mime_types = array('toto');
        $x = new  $this->myClass($upload_dir, $allowed_mime_types);
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('Xoops\Core\MediaUploader', $x);
    }
}
