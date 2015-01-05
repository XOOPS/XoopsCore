<?php
/**
 * XOOPS securityToken Smarty compiler plug-in
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Richard Griffith <richard@geekwright.com>
 */

/**
 * Inserts a XOOPS security token
 *
 * Not sure if this is a good idea (sounds like application logic, not presentation,)
 * but there are several token generations done in {php} tags which don't work with
 * Smarty 3.1
 */

function smarty_compiler_securityToken($params, Smarty $smarty)
{
//$xoops->events()->triggerEvent('debug.log', $path);
    return "<?php echo '" . \Xoops::getInstance()->security()->getTokenHTML() . "'; ?>";

}
