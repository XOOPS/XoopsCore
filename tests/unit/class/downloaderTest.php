<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class DownloaderAbstractTest extends \PHPUnit_Framework_TestCase
{ 
    public function test___construct()
	{
        // abstract class -> no test
    }
}
