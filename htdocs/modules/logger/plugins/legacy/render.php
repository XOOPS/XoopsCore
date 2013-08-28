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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         logger
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();
/* @var $this LoggerLegacy */
$ret = '';
if ($mode == 'popup') {
    $dump = $this->dump('');
    $content = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="content-language" content="' . XoopsLocale::getLangCode() . '" />
    <meta http-equiv="content-type" content="text/html; charset=' . XoopsLocale::getCharset() . '" />
    <title>' . $xoops->getConfig('sitename') . ' - ' . _MD_LOGGER_DEBUG . ' </title>
    <meta name="generator" content="XOOPS" />
    <link rel="stylesheet" type="text/css" media="all" href="' . $xoops->getCss($xoops->getConfig('theme_set')) . '" />
</head>
<body>' . $dump . '
    <div style="text-align:center;">
        <input class="formButton" value="' . XoopsLocale::A_CLOSE . '" type="button" onclick="javascript:window.close();" />
    </div>
';
    $ret .= '
<script type="text/javascript">
    debug_window = openWithSelfMain("about:blank", "popup", 680, 450, true);
    debug_window.document.clear();
';
    $lines = preg_split("/(\r\n|\r|\n)( *)/", $content);
    foreach ($lines as $line) {
        $ret .= "\n" . 'debug_window.document.writeln("' . str_replace(
            array('"', '</'), array('\"', '<\/'), $line) . '");';
    }
    $ret .= '
    debug_window.focus();
    debug_window.document.close();
</script>
';
}

$this->addExtra(_MD_LOGGER_INCLUDED_FILES, sprintf(_MD_LOGGER_FILES, count(get_included_files())));
/*
$included_files = get_included_files();
foreach ($included_files as $filename) {
    $this->addExtra('files',$filename);
}

if (function_exists('memory_get_peak_usage')) {
    $this->addExtra('Peak memory',memory_get_peak_usage());
}

*/
$memory = 0;

if (function_exists('memory_get_usage')) {
    $memory = memory_get_usage() . ' bytes';
} else {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $out = array();
        exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $out);
        if (isset($out[5])) {
            $memory = sprintf(_MD_LOGGER_MEM_ESTIMATED, substr($out[5], strpos($out[5], ':') + 1));
        }
    }
}
if ($memory) {
    $this->addExtra(_MD_LOGGER_MEM_USAGE, $memory);
}

if (empty($mode)) {
    $views = array('errors', 'deprecated', 'queries', 'blocks', 'extra');
    $ret .= "\n<div id=\"xo-logger-output\">\n<div id='xo-logger-tabs'>\n";
    $ret .= "<a href='javascript:xoSetLoggerView(\"none\")'>" . _MD_LOGGER_NONE . "</a>\n";
    $ret .= "<a href='javascript:xoSetLoggerView(\"\")'>" . _MD_LOGGER_ALL . "</a>\n";
    foreach ($views as $view) {
        $count = count($this->$view);
        $ret .= "<a href='javascript:xoSetLoggerView(\"$view\")'>" . constant('_MD_LOGGER_' . strtoupper($view)) . " ($count)</a>\n";
    }
    $count = count($this->logstart);
    $ret .= "<a href='javascript:xoSetLoggerView(\"timers\")'>" . _MD_LOGGER_TIMERS . "($count)</a>\n";
    $ret .= "</div>\n";
}

if (empty($mode) || $mode == 'errors') {
    $types = array(
        E_USER_NOTICE => _MD_LOGGER_E_USER_ERROR, E_NOTICE => _MD_LOGGER_E_NOTICE, E_WARNING => _MD_LOGGER_E_WARNING,
        E_STRICT => _MD_LOGGER_E_STRICT
    );
    $class = 'even';
    $ret .= '<table id="xo-logger-errors" class="outer"><thead><tr><th>' . _MD_LOGGER_ERRORS . '</th></tr></thead><tbody>';
    foreach ($this->errors as $error) {
        $ret .= "\n<tr><td class='$class'>";
        $ret .= isset($types[$error['errno']]) ? $types[$error['errno']] : _MD_LOGGER_UNKNOWN;
        $ret .= ": ";
        $ret .= sprintf(_MD_LOGGER_FILELINE, $this->sanitizePath($error['errstr']), $this->sanitizePath($error['errfile']), $error['errline']);
        $ret .= "<br />\n</td></tr>";
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= "\n</tbody></table>\n";
}

if (empty($mode) || $mode == 'deprecated') {
    $class = 'even';
    $ret .= '<table id="xo-logger-deprecated" class="outer"><thead><tr><th>' . _MD_LOGGER_DEPRECATED . '</th></tr></thead><tbody>';
    foreach ($this->deprecated as $message) {
        $ret .= "\n<tr><td class='$class'>";
        $ret .= $message;
        $ret .= "<br />\n</td></tr>";
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= "\n</tbody></table>\n";
}

if (empty($mode) || $mode == 'queries') {
    $class = 'even';
    $ret .= '<table id="xo-logger-queries" class="outer"><thead><tr><th>' . _MD_LOGGER_QUERIES . '</th></tr></thead><tbody>';
    $pattern = '/\b' . preg_quote(XOOPS_DB_PREFIX) . '\_/i';

    foreach ($this->queries as $q) {
        $sql = preg_replace($pattern, '', $q['sql']);
        $query_time = isset($q['query_time']) ? sprintf('%0.6f - ', $q['query_time']) : '';

        if (isset($q['error'])) {
            $ret .= '<tr class="' . $class . '"><td><span style="color:#ff0000;">' . $query_time . htmlentities($sql) . '<br /><strong>Error number:</strong> ' . $q['errno'] . '<br /><strong>Error message:</strong> ' . $q['error'] . '</span></td></tr>';
        } else {
            $ret .= '<tr class="' . $class . '"><td>' . $query_time . htmlentities($sql) . '</td></tr>';
        }

        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '</tbody><tfoot><tr class="foot"><td>' . _MD_LOGGER_TOTAL . ': <span style="color:#ff0000;">' . count($this->queries) . '</span></td></tr></tfoot></table>';
}
if (empty($mode) || $mode == 'blocks') {
    $class = 'even';
    $ret .= '<table id="xo-logger-blocks" class="outer"><thead><tr><th>' . _MD_LOGGER_BLOCKS . '</th></tr></thead><tbody>';
    foreach ($this->blocks as $b) {
        if ($b['cached']) {
            $ret .= '<tr><td class="' . $class . '"><strong>' . $b['name'] . ':</strong> ' . sprintf(_MD_LOGGER_CACHED, intval($b['cachetime'])) . '</td></tr>';
        } else {
            $ret .= '<tr><td class="' . $class . '"><strong>' . $b['name'] . ':</strong> ' . _MD_LOGGER_NOT_CACHED . '</td></tr>';
        }
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '</tbody><tfoot><tr class="foot"><td>' . _MD_LOGGER_TOTAL . ': <span style="color:#ff0000;">' . count($this->blocks) . '</span></td></tr></tfoot></table>';
}
if (empty($mode) || $mode == 'extra') {
    $class = 'even';
    $ret .= '<table id="xo-logger-extra" class="outer"><thead><tr><th>' . _MD_LOGGER_EXTRA . '</th></tr></thead><tbody>';
    foreach ($this->extra as $ex) {
        $ret .= '<tr><td class="' . $class . '"><strong>';
        $ret .= htmlspecialchars($ex['name']) . ':</strong> ' . htmlspecialchars($ex['msg']);
        $ret .= '</td></tr>';
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '</tbody></table>';
}
if (empty($mode) || $mode == 'timers') {
    $class = 'even';
    $ret .= '<table id="xo-logger-timers" class="outer"><thead><tr><th>' . _MD_LOGGER_TIMERS . '</th></tr></thead><tbody>';
    foreach ($this->logstart as $k => $v) {
        $ret .= '<tr><td class="' . $class . '"><strong>';
        $ret .= sprintf(_MD_LOGGER_TIMETOLOAD, htmlspecialchars($k) . '</strong>', '<span style="color:#ff0000;">' . sprintf("%.03f", $this->dumpTime($k)) . '</span>');
        $ret .= '</td></tr>';
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '</tbody></table>';
}

if (empty($mode)) {
    $ret .= <<<EOT
</div>
<script type="text/javascript">
    function xoLogCreateCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }
    function xoLogReadCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    function xoLogEraseCookie(name) {
        createCookie(name,"",-1);
    }
    function xoSetLoggerView( name ) {
        var log = document.getElementById( "xo-logger-output" );
        if ( !log ) return;
        var i, elt;
        for ( i=0; i!=log.childNodes.length; i++ ) {
            elt = log.childNodes[i];
            if ( elt.tagName && elt.tagName.toLowerCase() != 'script' && elt.id != "xo-logger-tabs" ) {
                elt.style.display = ( !name || elt.id == "xo-logger-" + name ) ? "block" : "none";
            }
        }
        xoLogCreateCookie( 'XOLOGGERVIEW', name, 1 );
    }
    xoSetLoggerView( xoLogReadCookie( 'XOLOGGERVIEW' ) );
</script>

EOT;
}