<?php
/**
 * xoAppUrl Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @package     xos_opal
 * @subpackage  xos_opal_Smarty
 * @since       2.0.14
 * @version     $Id$
 */

/**
 * Inserts the URL of an application page
 *
 * This plug-in allows you to generate a module location URL. It uses any URL rewriting
 * mechanism and rules you'll have configured for the system.
 *
 * To ensure this can be as optimized as possible, it accepts 2 modes of operation:
 *
 * <b>Static address generation</b>:<br>
 * This is the default mode and fastest mode. When used, the URL is generated during
 * the template compilation, and statically written in the compiled template file.
 * To use it, you just need to provide a location in a format XOOPS understands.
 *
 * <code>
 * // Generate an URL using a physical path
 * ([xoAppUrl modules/something/yourpage.php])
 * // Generate an URL using a module+location identifier (2.3+)
 * ([xoAppUrl mod_xoops_Identification#logout])
 * </code>
 *
 * <b>Dynamic address generation</b>:<br>
 * The is the slowest mode, and its use should be prevented unless necessary. Here,
 * the URL is generated dynamically each time the template is displayed, thus allowing
 * you to use the value of a template variable in the location string. To use it, you
 * must surround your location with double-quotes ("), and use the
 * {@link http://smarty.php.net/manual/en/language.syntax.quotes.php Smarty quoted strings}
 * syntax to insert variables values.
 *
 * <code>
 * // Use the value of the $sortby template variable in the URL
 * ([xoAppUrl "modules/something/yourpage.php?order=`$sortby`"])
 * </code>
 */
function smarty_compiler_xoAppUrl($params, Smarty $smarty)
{
    $xoops = Xoops::getInstance();
    $arg = reset($params);
    $url = trim($arg, " '\"\t\n\r\0\x0B");

    if (substr($url, 0, 1) == '/') {
        $url = 'www' . $url;
    }
    return "<?php echo '" . addslashes(htmlspecialchars($xoops->path($url, true))) . "'; ?>";
/*
    // Static URL generation
    if (strpos($argStr, '$') === false && $url != '.') {
        if (isset($params)) {
            $params = $compiler->smarty_compiler_xoAppUrl_parse_attrs($params, false);
            foreach ($params as $k => $v) {
                if (in_array(substr($v, 0, 1), array('"', "'"))) {
                    $params[$k] = substr($v, 1, -1);
                }
            }
            $url = $xoops->buildUrl($url, $params);
        }
        $url = $xoops->path($url, true);
        return "<?php echo '" . addslashes(htmlspecialchars($xoops->path($url, true))) . "'; ?>";
    }
    // Dynamic URL generation
    if ($url == '.') {
        $str = "\$_SERVER['REQUEST_URI']";
    } else {
        $str = "\$xoops->path('$url', true)";
    }
    if (isset($params)) {
        $params = $compiler->smarty_compiler_xoAppUrl_parse_attrs($params, false);
        $str = "\$xoops->buildUrl($str, array(\n";
        foreach ($params as $k => $v) {
            $str .= var_export($k, true) . " => $v,\n";
        }
        $str .= "))";
    }
    return "<?php echo \"" . htmlspecialchars($str) . "\" ?>";
*/
}

    /**
     * Parse attribute string
     *
     * @param string $tag_args
     * @return array
     */
    function smarty_compiler_xoAppUrl_parse_attrs($tag_args)
    {

        /* Tokenize tag attributes. */
        preg_match_all('~(?:' . $this->_obj_call_regexp . '|' . $this->_qstr_regexp . ' | (?>[^"\'=\s]+)
                         )+ |
                         [=]
                        ~x', $tag_args, $match);
        $tokens       = $match[0];

        $attrs = array();
        /* Parse state:
            0 - expecting attribute name
            1 - expecting '='
            2 - expecting attribute value (not '=') */
        $state = 0;

        foreach ($tokens as $token) {
            switch ($state) {
                case 0:
                    /* If the token is a valid identifier, we set attribute name
                       and go to state 1. */
                    if (preg_match('~^\w+$~', $token)) {
                        $attr_name = $token;
                        $state = 1;
                    } else
                        $this->_syntax_error("invalid attribute name: '$token'", E_USER_ERROR, __FILE__, __LINE__);
                    break;

                case 1:
                    /* If the token is '=', then we go to state 2. */
                    if ($token == '=') {
                        $state = 2;
                    } else
                        $this->_syntax_error("expecting '=' after attribute name '$last_token'", E_USER_ERROR, __FILE__, __LINE__);
                    break;

                case 2:
                    /* If token is not '=', we set the attribute value and go to
                       state 0. */
                    if ($token != '=') {
                        /* We booleanize the token if it's a non-quoted possible
                           boolean value. */
                        if (preg_match('~^(on|yes|true)$~', $token)) {
                            $token = 'true';
                        } else if (preg_match('~^(off|no|false)$~', $token)) {
                            $token = 'false';
                        } else if ($token == 'null') {
                            $token = 'null';
                        } else if (preg_match('~^' . $this->_num_const_regexp . '|0[xX][0-9a-fA-F]+$~', $token)) {
                            /* treat integer literally */
                        } else if (!preg_match('~^' . $this->_obj_call_regexp . '|' . $this->_var_regexp . '(?:' . $this->_mod_regexp . ')*$~', $token)) {
                            /* treat as a string, double-quote it escaping quotes */
                            $token = '"'.addslashes($token).'"';
                        }

                        $attrs[$attr_name] = $token;
                        $state = 0;
                    } else
                        $this->_syntax_error("'=' cannot be an attribute value", E_USER_ERROR, __FILE__, __LINE__);
                    break;
            }
            $last_token = $token;
        }

        if($state != 0) {
            if($state == 1) {
                $this->_syntax_error("expecting '=' after attribute name '$last_token'", E_USER_ERROR, __FILE__, __LINE__);
            } else {
                $this->_syntax_error("missing attribute value", E_USER_ERROR, __FILE__, __LINE__);
            }
        }

        $this->_parse_vars_props($attrs);

        return $attrs;
    }
