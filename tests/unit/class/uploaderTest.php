<?php
require_once(__DIR__ . '/../init_new.php');

class UploaderTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsMediaUploader';

    protected function setUp()
    {
    }

    public function test___construct()
    {
        $upload_dir = 'upload_dir';
        $allowed_mime_types = ['toto'];
        $x = new  $this->myClass($upload_dir, $allowed_mime_types);
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('\\Xoops\\Core\\MediaUploader', $x);
    }
}
