<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/class/class.tar.php');
 
class TestOfTar extends UnitTestCase
{
    protected $myclass='Tar';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $tar = new $this->myclass();
        //$tar->openTAR();
        $this->assertEqual(true, false);
    }
    
    public function test_120() {
        $tar = new $this->myclass();
        //$tar->appendTAR();
        $this->assertEqual(true, false);
    }
    
    public function test_140() {
        $tar = new $this->myclass();
        //$tar->getFile($filename);
        $this->assertEqual(true, false);
    }
    
    public function test_160() {
        $tar = new $this->myclass();
        //$tar->getDirectory($dirname);
        $this->assertEqual(true, false);
    }
    
    public function test_180() {
        $tar = new $this->myclass();
        //$tar->containsFile($filename);
        $this->assertEqual(true, false);
    }
    
    public function test_200() {
        $tar = new $this->myclass();
        //$tar->containsDirectory($dirname);
        $this->assertEqual(true, false);
    }
    
    public function test_220() {
        $tar = new $this->myclass();
        //$tar->addDirectory($dirname);
        $this->assertEqual(true, false);
    }
    
    public function test_240() {
        $tar = new $this->myclass();
        //$tar->addFile($filename,$binary);
        $this->assertEqual(true, false);
    }
    
    public function test_260() {
        $tar = new $this->myclass();
        //$tar->removeFile($filename);
        $this->assertEqual(true, false);
    }
    
     public function test_280() {
        $tar = new $this->myclass();
        //$tar->removeDirectory($dirname);
        $this->assertEqual(true, false);
    }
    
     public function test_300() {
        $tar = new $this->myclass();
        //$tar->saveTar();
        $this->assertEqual(true, false);
    }
    
     public function test_320() {
        $tar = new $this->myclass();
        //$tar->toTar();
        $this->assertEqual(true, false);
    }
    
     public function test_340() {
        $tar = new $this->myclass();
        //$tar->toTarOutput();
        $this->assertEqual(true, false);
    }
    
}
