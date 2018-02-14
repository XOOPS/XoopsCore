<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\XoopsTpl;

/**
 *  Usage : just place {xoblock id=1} inside any template or theme, replace '1' with the id of the block you want to show
 *
 *  Other options:
 *  display = 'title' -> shows just title
 *  display = 'none' -> renders the block but does not display it
 *  options = 'enter|block|options' -> overwrites block default options
 *  groups = 'enter|allowed|groups' -> overwrites block default group view permissions
 *  cache = 3600 -> overwrite cache time(in seconds)
 *
 *  Examples:
 *  {xoblock id=1 display="title"}   displays just the block title
 *  {xoblock id=1}                   displays just the block content
 *  {xoblock id=7 display="none"}    does not display nothing but executes the block, this can go for online block or to trigger some cron block
 *  {xoblock id=600 groups="0|1" cache=20}  display block just for this 2 groups and sets a cache of 20 seconds
 *  {block id=600 options="100|100|s_poweredby.gif|0"} displays block with diferent options
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      trabis <lusopoemas@gmail.com>
 * @version     $Id$
 */

/**
 * @param array  $params
 * @param Smarty $smarty
 * @return bool|mixed|string
 */
function smarty_function_xoblock($params, &$smarty)
{
    if (!isset($params['id'])) {
        return false;
    }

    $xoops = Xoops::getInstance();

    $display_title = (isset($params['display']) && $params['display'] === 'title') ? true : false;
    $display_none = (isset($params['display']) && $params['display'] === 'none') ? true : false;
    $options = (isset($params['options'])) ? $params['options'] : false;
    $groups = (isset($params['groups'])) ? explode('|', $params['groups']) : false;
    $cache = (isset($params['cache'])) ? (int)($params['cache']) : false;

    $block_id = (int)($params['id']);

    $block_handler = $xoops->getHandlerBlock();
    static $block_objs;
    if (!isset($block_objs[$block_id])) {
        $blockObj = $block_handler->get($block_id);
        if (!is_object($blockObj)) {
            return false;
        }
        $block_objs[$block_id] = $blockObj;
    } else {
        $blockObj = $block_objs[$block_id];
    }
    $user_groups = $xoops->getUserGroups();

    static $allowed_blocks;
    if (count($allowed_blocks) == 0) {
        $allowed_blocks = $block_handler->getAllBlocksByGroup($user_groups, false);
    }

    if ($groups) {
        if (!array_intersect($user_groups, $groups)) {
            return false;
        }
    } else {
        if (!in_array($block_id, $allowed_blocks)) {
            return false;
        }
    }

    if ($options) {
        $blockObj->setVar('options', $options);
    }

    if ($cache) {
        $blockObj->setVar('bcachetime', $cache);
    }

    if ($display_title) {
        return $blockObj->getVar('title');
    }

    $tpl = new XoopsTpl();
    $block_renderer = new \Xoops\Core\Theme\Plugins\Blocks();
    $block_renderer->theme = $xoops->theme();
    $block = $block_renderer->buildBlock($blockObj, $tpl);
    if (!$display_none) {
        return $block['content'];
    }
    return '';
}
