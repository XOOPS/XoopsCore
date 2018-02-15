<?php
require_once(__DIR__.'/../init_new.php');

class XoopsZipDownloaderTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsZipDownloader';
    
    public function test___construct()
    {
        $class = $this->myClass;
        $x = new $class();
        $this->assertInstanceOf($this->myClass, $x);
        $this->assertInstanceOf('XoopsDownloader', $x);
    }
}
