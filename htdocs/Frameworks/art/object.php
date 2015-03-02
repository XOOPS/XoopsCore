<?php
/**
 * Extended object handlers
 *
 * For backward compat
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      art
 */

//if (!class_exists("ArtObject")):
if (class_exists("ArtObject")) return;


/**
 * Art Object
 *
 * @author D.J. (phppp)
 * @copyright copyright &copy; 2005 XoopsForge.com
 * @package module::article
 *
 * {@link XoopsObject}
 **/

class ArtObject extends XoopsObject
{
    /**
     * @var string
     */
    var $plugin_path;

    /**
     * Constructor
     *
     */
    function ArtObject()
    {
    }
}

/**
* object handler class.
* @package module::article
*
* @author  D.J. (phppp)
* @copyright copyright &copy; 2000 The XOOPS Project
*
* {@link XoopsPersistableObjectHandler}
*
*/

class ArtObjectHandler extends XoopsPersistableObjectHandler
{

    var $db;

     /**
     * Constructor
     *
     * @param object $db reference to the {@link Xoops\Core\Database\Connection} object
     **/

    function __construct($db, $table, $className, $keyName, $identifierName)
    {
        $this->db = $db;
        parent::__construct($db, $table, $className, $keyName, $identifierName);
    }

    function ArtObjectHandler($db, $table = "", $className = "", $keyName = "", $identifierName = false)
    {
        $this->__construct( $db, $table, $className, $keyName, $identifierName );
    }

    /**
     * get MySQL server version
     *
     * @return     string
     */
    function mysql_server_version($conn = null)
    {
        if (!is_null($conn)) {
            return mysql_get_server_info($conn);
        } else {
            return mysql_get_server_info();
        }
    }

    /**
     * get MySQL major version
     *
     * @return     integer    : 3 - 4.1-; 4 - 4.1+; 5 - 5.0+
     */
    function mysql_major_version()
    {
        $version = $this->mysql_server_version();
        if (version_compare( $version, "5.0.0", "ge" ) ) $mysql_version = 5;
        elseif (version_compare( $version, "4.1.0", "ge" ) ) $mysql_version = 4;
        else $mysql_version = 3;
        return $mysql_version;
    }

    function insert(&$object, $force = true)
    {
        if ($ret = parent::insert($object, $force)) {
            $object->unsetNew();
        }
        return $ret;
    }
}
//endif;
