<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SessionTest extends MY_UnitTestCase
{
    var $myclass='XoopsSession';

    public function SetUp()
	{
    }

    public function test___construct()
	{
    }
}
