<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * XOOPS global search
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$search = Search::getInstance();
if (!$search->getConfig('enable_search')) {
    header('Location: ' . XOOPS_URL . '/index.php');
    exit();
}

$xoops = Xoops::getInstance();

$action = Request::getCmd('action', 'search');
$query = Request::getString('query', '');
$andor = Request::getWord('query', 'AND');
$mid = Request::getInt('mid', 0);
$uid = Request::getInt('uid', 0);
$start = Request::getInt('start', 0);
$mids = Request::getArray('mids', array());

$queries = array();

if ($action == "results") {
    if ($query == "") {
        $xoops->redirect("index.php", 1, _MD_SEARCH_PLZENTER);
    }
} else {
    if ($action == "showall") {
        if ($query == "" || empty($mid)) {
            $xoops->redirect("index.php", 1, _MD_SEARCH_PLZENTER);
        }
    } else {
        if ($action == "showallbyuser") {
            if (empty($mid) || empty($uid)) {
                $xoops->redirect("index.php", 1, _MD_SEARCH_PLZENTER);
            }
        }
    }
}

$gperm_handler = $xoops->getHandlerGroupperm();
$available_modules = $gperm_handler->getItemIds('module_read', $search->getUserGroups());
$available_plugins = \Xoops\Module\Plugin::getPlugins('search');

if ($action == 'search') {
    $xoops->header();

    /* @var $formHandler SearchSearchForm */
    $formHandler = $search->getForm(null, 'search');
    $form = $formHandler->getSearchFrom($andor, $queries, $mids, $mid);
    $form->display();

    $xoops->footer();
}
if ($andor != "OR" && $andor != "exact" && $andor != "AND") {
    $andor = "AND";
}

$ignored_queries = array(); // holds kewords that are shorter than allowed minmum length
$queries_pattern = array();
$myts = MyTextSanitizer::getInstance();
if ($action != 'showallbyuser') {
    if ($andor != "exact") {
        //$temp_queries = preg_split('/[\s,]+/', $query);
        $temp_queries = str_getcsv($query, ' ', '"');
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (mb_strlen($q) >= $search->getConfig('keyword_min')) {
                $queries[] = $myts->addSlashes($q);
                $queries_pattern[] = '~(' . $q . ')~sUi';
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (count($queries) == 0) {
            $xoops->redirect('index.php', 2, sprintf(_MD_SEARCH_KEYTOOSHORT, $search->getConfig('keyword_min')));
        }
    } else {
        $query = trim($query);
        if (mb_strlen($query) < $search->getConfig('keyword_min')) {
            $xoops->redirect('index.php', 2, sprintf(_MD_SEARCH_KEYTOOSHORT, $search->getConfig('keyword_min')));
        }
        $queries = array($myts->addSlashes($query));
        $queries_pattern[] = '~(' . $myts->addSlashes($query) . ')~sUi';
    }
}

switch ($action) {
    case "results":
        $module_handler = $xoops->getHandlerModule();
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('dirname', "('" . implode("','", array_keys($available_plugins)) . "')", 'IN'));
        $modules = $module_handler->getObjectsArray($criteria, true);
        if (empty($mids) || !is_array($mids)) {
            unset($mids);
            $mids = array_keys($modules);
        }
        $xoops->header('module:search/search.tpl');
        $nomatch = true;
        $xoops->tpl()->assign('search', true);
        $xoops->tpl()->assign('queries', $queries);
        $xoops->tpl()->assign('ignored_words', sprintf(_MD_SEARCH_IGNOREDWORDS, $search->getConfig('keyword_min')));
        $xoops->tpl()->assign('ignored_queries', $ignored_queries);

        $modules_result = array();
        foreach ($mids as $mid) {
            $mid = intval($mid);
            /* @var $module XoopsModule */
            $module = $modules[$mid];
            /* @var $plugin SearchPluginInterface */
            $plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'search');
            $results = $plugin->search($queries, $andor, 5, 0, null);
            $count = count($results);
            $mid = $module->getVar('mid');

            $res = array();
            if (is_array($results) && $count > 0) {
                $nomatch = false;
                $modules_result[$mid]['name'] = $module->getVar('name');
                if (XoopsLoad::fileExists($image = $xoops->path('modules/' . $module->getVar('dirname') . '/icons/logo_large.png'))) {
                    $modules_result[$mid]['image'] = $xoops->url($image);
                } else {
                    $modules_result[$mid]['image'] = $xoops->url('images/icons/posticon2.gif');
                }
                $res = array();
                for ($i = 0; $i < $count; $i++) {
                    if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                        $res[$i]['link'] = $xoops->url('modules/' . $module->getVar('dirname') . '/' . $results[$i]['link']);
                    } else {
                        $res[$i]['link'] = $results[$i]['link'];
                    }
                    $res[$i]['title'] = $myts->htmlspecialchars($results[$i]['title']);
                    $res[$i]['title_highligh'] = preg_replace($queries_pattern, "<span class='searchHighlight'>$1</span>", $myts->htmlspecialchars($results[$i]['title']));
                    if (!empty($results[$i]['uid'])) {
                        $res[$i]['uid'] = intval($results[$i]['uid']);
                        $res[$i]['uname'] = XoopsUser::getUnameFromId($results[$i]['uid'], true);
                    }
                    $res[$i]['time'] = !empty($results[$i]['time']) ? XoopsLocale::formatTimestamp(intval($results[$i]['time'])) : "";
                    $res[$i]['content'] = empty($results[$i]['content']) ? "" : preg_replace($queries_pattern, "<span class='searchHighlight'>$1</span>", $results[$i]['content']);
                }
                if ($count >= 5) {
                    $search_url = $search->url('index.php?query=' . urlencode(stripslashes(implode(' ', $queries))));
                    $search_url .= "&mid={$mid}&action=showall&andor={$andor}";
                    $modules_result[$mid]['search_url'] = htmlspecialchars($search_url);
                }
            }
            if (count($res) > 0) {
                $modules_result[$mid]['result'] = $res;
            }
        }
        unset($results);
        unset($module);

        $xoops->tpl()->assign('modules', $modules_result);

        /* @var $formHandler SearchSearchForm */
        $formHandler = $search->getForm(null, 'search');
        $form = $formHandler->getSearchFrom($andor, $queries, $mids, $mid);
        $form->display();
        break;

    case "showall":
    case 'showallbyuser':
        $xoops->header('module:search/search.tpl');
        $xoops->tpl()->assign('search', true);
        $xoops->tpl()->assign('queries', $queries);
        $xoops->tpl()->assign('ignored_words', sprintf(_MD_SEARCH_IGNOREDWORDS, $search->getConfig('keyword_min')));
        $xoops->tpl()->assign('ignored_queries', $ignored_queries);

        $module_handler = $xoops->getHandlerModule();
        $module = $xoops->getModuleById($mid);
        /* @var $plugin SearchPluginInterface */
        $plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'search');
        $results = $plugin->search($queries, $andor, 20, $start, $uid);

        $modules_result[$mid]['name'] = $module->getVar('name');
        $modules_result[$mid]['image'] = $xoops->url('modules/' . $module->getVar('dirname') . '/icons/logo_large.png');

        $count = count($results);
        if (is_array($results) && $count > 0) {
            $next_results = $plugin->search($queries, $andor, 1, $start + 20, $uid);
            $next_count = count($next_results);
            $has_next = false;
            if (is_array($next_results) && $next_count == 1) {
                $has_next = true;
            }
            $xoops->tpl()->assign('sr_showing', sprintf(_MD_SEARCH_SHOWING, $start + 1, $start + $count));
            $res = array();
            for ($i = 0; $i < $count; $i++) {
                if (isset($results[$i]['image']) && $results[$i]['image'] != "") {
                    $res[$i]['image'] = $xoops->url('modules/' . $module->getVar('dirname') . '/' . $results[$i]['image']);
                } else {
                    $res[$i]['image'] = $xoops->url('images/icons/posticon2.gif');
                }
                if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                    $res[$i]['link'] = $xoops->url('modules/' . $module->getVar('dirname') . '/' . $results[$i]['link']);
                } else {
                    $res[$i]['link'] = $results[$i]['link'];
                }
                $res[$i]['title'] = $myts->htmlspecialchars($results[$i]['title']);
                if (isset($queries_pattern)) {
                    $res[$i]['title_highligh'] = preg_replace($queries_pattern, "<span class='searchHighlight'>$1</span>", $myts->htmlspecialchars($results[$i]['title']));
                } else {
                    $res[$i]['title_highligh'] = $myts->htmlspecialchars($results[$i]['title']);
                }
                if (!empty($results[$i]['uid'])) {
                    $res[$i]['uid'] = @intval($results[$i]['uid']);
                    $res[$i]['uname'] = XoopsUser::getUnameFromId($results[$i]['uid'], true);
                }
                $res[$i]['time'] = !empty($results[$i]['time']) ? " (" . XoopsLocale::formatTimestamp(intval($results[$i]['time'])) . ")" : "";
                $res[$i]['content'] = empty($results[$i]['content']) ? "" : preg_replace($queries_pattern, "<span class='searchHighlight'>$1</span>", $results[$i]['content']);
            }
            if (count($res) > 0) {
                $modules_result[$mid]['result'] = $res;
            }

            $search_url = $search->url('index.php?query=' . urlencode(stripslashes(implode(' ', $queries))));
            $search_url .= "&mid={$mid}&action={$action}&andor={$andor}";
            if ($action == 'showallbyuser') {
                $search_url .= "&uid={$uid}";
            }
            if ($start > 0) {
                $prev = $start - 20;
                $search_url_prev = $search_url . "&start={$prev}";
                $modules_result[$mid]['prev'] = htmlspecialchars($search_url_prev);
            }
            if (false != $has_next) {
                $next = $start + 20;
                $search_url_next = $search_url . "&start={$next}";
                $modules_result[$mid]['next'] = htmlspecialchars($search_url_next);
            }
            $xoops->tpl()->assign('modules', $modules_result);
        }

        /* @var $formHandler SearchSearchForm */
        $formHandler = $search->getForm(null, 'search');
        $form = $formHandler->getSearchFrom($andor, $queries, $mids, $mid);
        $form->display();
        break;
}
$xoops->footer();
