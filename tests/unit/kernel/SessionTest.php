<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SessionTest extends \PHPUnit_Framework_TestCase
{
    var $myclass='XoopsSession';

    public function SetUp()
	{
    }

    public function test___construct()
	{
    }
}
