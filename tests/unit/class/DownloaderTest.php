<?php
require_once(dirname(__FILE__).'/../init.php');

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
