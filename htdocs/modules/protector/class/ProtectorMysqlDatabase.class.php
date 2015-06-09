<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Protector
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

$root_path = \XoopsBaseConfig::get('root-path');
$db_type = \XoopsBaseConfig::get('db-type');

if( XoopsLoad::fileExists( $root_path.'/class/database/drivers/'.$db_type.'/database.php' ) ) {
    require_once $root_path.'/class/database/drivers/'.$db_type.'/database.php';
} else {
    require_once $root_path.'/class/database/'.$db_type.'database.php';
}

require_once $root_path.'/class/database/database.php' ;

class ProtectorMySQLDatabase extends XoopsMySQLDatabaseProxy
{

var $doubtful_requests = array() ;
var $doubtful_needles = array(
    // 'order by' ,
    'concat' ,
    'information_schema' ,
    'select' ,
    'union' ,
    '/*' , /**/
    '--' ,
    '#' ,
) ;


function ProtectorMySQLDatabase()
{
    $protector = Protector::getInstance() ;
    $this->doubtful_requests = $protector->getDblayertrapDoubtfuls() ;
    $this->doubtful_needles = array_merge( $this->doubtful_needles , $this->doubtful_requests ) ;
}


function injectionFound( $sql )
{
    $protector = Protector::getInstance() ;

    $protector->last_error_type = 'SQL Injection' ;
    $protector->message .= $sql ;
    $protector->output_log( $protector->last_error_type ) ;
    die( 'SQL Injection found' ) ;
}


function separateStringsInSQL( $sql )
{
    $sql = trim( $sql ) ;
    $sql_len = strlen( $sql ) ;
    $char = '' ;
    $string_start = '' ;
    $in_string = false;
    $sql_wo_string = '' ;
    $strings = array() ;
    $current_string = '' ;

    for( $i = 0 ; $i < $sql_len ; ++$i ) {
        $char = $sql[$i] ;
        if( $in_string ) {
            while( 1 ) {
                $new_i = strpos( $sql , $string_start , $i ) ;
                $current_string .= substr( $sql , $i , $new_i - $i + 1 ) ;
                $i = $new_i ;
                if( $i === false ) {
                    break 2 ;
                } else if( /* $string_start == '`' || */ $sql[$i-1] != '\\' ) {
                    $string_start = '' ;
                    $in_string = false ;
                    $strings[] = $current_string ;
                    break ;
                } else {
                    $j = 2 ;
                    $escaped_backslash = false ;
                    while( $i - $j > 0 && $sql[$i-$j] == '\\' ) {
                        $escaped_backslash = ! $escaped_backslash ;
                        ++$j;
                    }
                    if ($escaped_backslash) {
                        $string_start = '' ;
                        $in_string = false ;
                        $strings[] = $current_string ;
                        break ;
                    } else {
                        ++$i;
                    }
                }
            }
        } else if( $char == '"' || $char == "'" ) { // dare to ignore ``
            $in_string = true ;
            $string_start = $char ;
            $current_string = $char ;
        } else {
            $sql_wo_string .= $char ;
        }
        // dare to ignore comment
        // because unescaped ' or " have been already checked in stage1
    }

    return array( $sql_wo_string , $strings ) ;
}



/**
 * @param string $sql
 */
function checkSql( $sql )
{
    list( $sql_wo_strings , $strings ) = $this->separateStringsInSQL( $sql ) ;

    // stage1: addslashes() processed or not
    foreach( $this->doubtful_requests as $request ) {
        if( addslashes( $request ) != $request ) {
            if( stristr( $sql , trim( $request ) ) ) {
                // check the request stayed inside of strings as whole
                $ok_flag = false ;
                foreach( $strings as $string ) {
                    if( strstr( $string , $request ) ) {
                        $ok_flag = true ;
                        break ;
                    }
                }
                if( ! $ok_flag ) {
                    $this->injectionFound( $sql ) ;
                }
            }
        }
    }

    // stage2: doubtful requests exists and outside of quotations ('or")
    // $_GET['d'] = '1 UNION SELECT ...'
    // NG: select a from b where c=$d
    // OK: select a from b where c='$d_escaped'
    // $_GET['d'] = '(select ... FROM)'
    // NG: select a from b where c=(select ... from)
    foreach( $this->doubtful_requests as $request ) {
        if( strstr( $sql_wo_strings , trim( $request ) ) ) {
            $this->injectionFound( $sql ) ;
        }
    }

    // stage3: comment exists or not without quoted strings (too sensitive?)
    if( preg_match( '/(\/\*|\-\-|\#)/' , $sql_wo_strings , $regs ) ) {
        foreach( $this->doubtful_requests as $request ) {
            if( strstr( $request , $regs[1] ) ) {
                $this->injectionFound( $sql ) ;
            }
        }
    }
}


function query( $sql , $limit = 0 , $start = 0 )
{
    $sql4check = substr( $sql , 7 ) ;
    foreach( $this->doubtful_needles as $needle ) {
        if( stristr( $sql4check , $needle ) ) {
            $this->checkSql( $sql ) ;
            break ;
        }
    }

    if( ! defined( 'XOOPS_DB_PROXY' ) ) {
        $ret = parent::queryF( $sql , $limit , $start ) ;
    } else {
        $ret = parent::query( $sql , $limit , $start ) ;
    }
    return $ret ;
}

}
