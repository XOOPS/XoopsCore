<?php
/**
 * xoAppUrl Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @package     xos_opal
 * @subpackage  xos_opal_Smarty
 * @since       2.0.14
 * @version     $Id$
 */

/**
 * Build application relative URL
 *
 * This plug-in allows you to generate a module location URL. It uses any URL rewriting
 * mechanism and rules you'll have configured for the system.
 *
 * <code>
 * // Generate an URL using a physical path
 * {xoAppUrl 'modules/something/yourpage.php'}
 * </code>
 *
 * The path should be in a form understood by Xoops::url()
 * @param $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_compiler_xoAppUrl($params, Smarty $smarty)
{
    $xoops = Xoops::getInstance();
    $arg = reset($params);
    $url = trim($arg, " '\"\t\n\r\0\x0B");

    if (substr($url, 0, 1) == '/') {
        $url = 'www' . $url;
    }
    return "<?php echo '" . addslashes(htmlspecialchars($xoops->url($url))) . "'; ?>";
}
