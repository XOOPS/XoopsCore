<?php

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Plugins_Xoops_quoteTest extends \PHPUnit_Framework_TestCase
{
    protected $buffer = null;
    
    public function __construct()
    {
        spl_autoload_register(array($this,'my_autoloader'),true,true);
    }
    
    public function my_autoloader($class)
    {
        static $init_done;
        
        if ($class == 'Doctrine\DBAL\Query\Expression\ExpressionBuilder') {
            require (dirname(__FILE__).'/../../../../../../MY_Doctrine/ExpressionBuilder.php');
        } elseif ($class == 'Doctrine\DBAL\Query\QueryBuilder') {
            require (dirname(__FILE__).'/../../../../../../MY_Doctrine/QueryBuilder.php');
            require (dirname(__FILE__).'/../../../../../../MY_Doctrine/ResultStatement.php');
            require (dirname(__FILE__).'/../../../../../../MY_Doctrine/Statement.php');
        }
        if ($init_done == null) {
            $init_done = true;
            require (dirname(__FILE__).'/../../../../../../init_new.php');
        }
    }
    
    public function output_callback($buffer, $flags)
    {
        $this->buffer = $buffer;
        return '';
    }
    
    public function test_100()
    {
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
		ob_start(array($this,'output_callback')); // to catch output after ob_end_flush in Xoops::simpleFooter
		require_once ($xoops_root_path.'/class/xoopseditor/tinymce/tiny_mce/plugins/xoops_quote/xoops_quote.php');
		$this->assertTrue(is_string($this->buffer));
    }
}
