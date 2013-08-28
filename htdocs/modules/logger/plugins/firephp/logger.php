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

include_once dirname(dirname(__FILE__)) . '/legacy/logger.php';

class LoggerFirephp extends LoggerLegacy
{
    function render($output)
    {
        if (!$this->activated) {
            return $output;
        }

        $xoops = Xoops::getInstance();

        require_once dirname(__FILE__) . '/FirePHPCore/FirePHP.class.php';
        if (XoopsLoad::fileExists($file = dirname(__FILE__) . '/language/' . $xoops->getConfig('language') . '.php')) {
            include_once $file;
        } else {
            include_once dirname(__FILE__) . '/language/english.php';
        }

        $fb = FirePHP::getInstance(true);
        $fb->registerErrorHandler(true);

        $included_files = get_included_files();
        foreach ($included_files as $filename) {
            $this->addExtra(_FIREPHP_LANG3, $filename);
        }

        $memory = 0;
        if (function_exists('memory_get_usage')) {
            $memory = memory_get_usage() . _FIREPHP_LANG4;
        } else {
            $os = isset($_ENV['OS']) ? $_ENV['OS'] : $_SERVER['OS'];
            if (strpos(strtolower($os), 'windows') !== false) {
                $out = array();
                exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $out);
                $memory = substr($out[5], strpos($out[5], ':') + 1) . ' [Estimated]';
            }
        }

        if ($memory) {
            $this->addExtra(_FIREPHP_LANG5, $memory);
        }

        $types = array(
            E_USER_NOTICE => _FIREPHP_LANG6, E_USER_ERROR => _FIREPHP_LANG7, E_NOTICE => _FIREPHP_LANG6,
            E_WARNING     => _FIREPHP_LANG8, E_STRICT => _FIREPHP_LANG9
        );
        $fberrors = array();
        foreach ($this->errors as $error) {
            $fberrorstype = isset($types[$error['errno']]) ? $types[$error['errno']] : 'Unknown';
            $fberrors[] = array($fberrorstype . sprintf(_FIREPHP_LANG18, $this->sanitizePath($error['errstr']), $this->sanitizePath($error['errfile']), $error['errline']));
        }

        if (!empty($fberrors)) {
            $fb->table(_FIREPHP_LANG10, $fberrors);
        }

        $fbqueries = array();
        $fbquerieserr = array();
        $pattern = '/b' . preg_quote(XOOPS_DB_PREFIX) . '_/i';
        $errcount = 1;

        foreach ($this->queries as $q) {

            $sql = preg_replace($pattern, '', $q['sql']);

            if (isset($q['error'])) {
                $errcount++;
                $fb->group(_FIREPHP_LANG13, array('Collapsed' => true, 'Color' => '#FF0000'));
                $fbquerieserr[] = array(htmlentities($sql) . _FIREPHP_LANG11 . $q['errno'] . _FIREPHP_LANG12 . $q['error']);
                $fb->groupEnd();
            } else {
                $fbqueries[] = array(htmlentities($sql));
            }
        }

        $fbqueries[] = array(_FIREPHP_LANG14 . count($this->queries) . _FIREPHP_LANG15);
        $fb->table(_FIREPHP_LANG16, $fbqueries);

        if ($errcount > 1) {
            $fb->table(_FIREPHP_LANG17, $fbquerieserr);
        }

        $fbblocks = array();
        foreach ($this->blocks as $b) {
            if ($b['cached']) {
                $fbblocks[] = array(htmlspecialchars($b['name']) . _FIREPHP_LANG19 . intval($b['cachetime']) . _FIREPHP_LANG20);
            } else {
                $fbblocks[] = array(htmlspecialchars($b['name']) . _FIREPHP_LANG21);
            }
        }

        $fbblocks[] = array(_FIREPHP_LANG14 . count($this->blocks) . _FIREPHP_LANG22);

        if (count($this->blocks) > 0) {
            $fb->table(_FIREPHP_LANG23, $fbblocks);
        }

        $fbextra = array();
        $fbextra[] = array(_FIREPHP_LANG1, count(get_included_files()) . _FIREPHP_LANG2);
        foreach ($this->extra as $ex) {
            $fbextra[] = array(htmlspecialchars($ex['name']), htmlspecialchars($ex['msg']));
        }

        $fb->table(_FIREPHP_LANG24, $fbextra);

        $fbtimers = array();
        $fbtimers[] = array(_FIREPHP_LANG28, _FIREPHP_LANG29);

        foreach ($this->logstart as $k => $v) {
            $fbtimers[] = array(htmlspecialchars($k), sprintf("%.03f", $this->dumpTime($k)) . _FIREPHP_LANG26);
        }

        $fb->table(_FIREPHP_LANG27, $fbtimers);
        return $output;
    }
}
