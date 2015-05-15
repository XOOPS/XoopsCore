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
 * XoopsThemeBlocksPlugin component class file
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @since           2.3.0
 * @package         class
 * @version         $Id$
 */

/**
 * XoopsThemeBlocksPlugin main class
 *
 * @package xos_logos
 * @subpackage XoopsThemeBlocksPlugin
 * @author Skalpa Keo
 * @since 2.3.0
 */
class XoopsThemeBlocksPlugin extends XoopsThemePlugin
{
    /**
     * @var XoopsTheme
     */
    public $theme = false;

    /**
     * @var array
     */
    public $blocks = array();

    /**
     * XoopsThemeBlocksPlugin::xoInit()
     *
     * @return boolean
     */
    public function xoInit()
    {
        $this->retrieveBlocks();
        if ($this->theme) {
            $this->theme->template->assignByRef('xoBlocks', $this->blocks);
        }
        return true;
    }

    /**
     * Called before a specific zone is rendered
     *
     * @param string $zone
     * @return void
     */
    public function preRender($zone = '')
    {
    }

    /**
     * Called after a specific zone is rendered
     *
     * @param string $zone
     * @return void
     */
    public function postRender($zone = '')
    {
    }

    /**
     * XoopsThemeBlocksPlugin::retrieveBlocks()
     *
     * @return void
     */
    public function retrieveBlocks()
    {
        $xoops = Xoops::getinstance();

        if ($xoops->isModule()) {
            $mid = $xoops->module->getVar('mid');
            $isStart = (substr($_SERVER['PHP_SELF'], -9) == 'index.php' && $xoops->getConfig('startpage') == $xoops->module->getVar('dirname') && empty($_SERVER['QUERY_STRING']));
        } else {
            $mid = 0;
            $isStart = $xoops->getOption('show_cblock');
        }

        $groups = $xoops->getUserGroups();

        $oldzones = array(
            XOOPS_SIDEBLOCK_LEFT => 'canvas_left', XOOPS_SIDEBLOCK_RIGHT => 'canvas_right',
            XOOPS_CENTERBLOCK_LEFT => 'page_topleft', XOOPS_CENTERBLOCK_CENTER => 'page_topcenter',
            XOOPS_CENTERBLOCK_RIGHT => 'page_topright', XOOPS_CENTERBLOCK_BOTTOMLEFT => 'page_bottomleft',
            XOOPS_CENTERBLOCK_BOTTOM => 'page_bottomcenter', XOOPS_CENTERBLOCK_BOTTOMRIGHT => 'page_bottomright'
        );
        foreach ($oldzones as $zone) {
            $this->blocks[$zone] = array();
        }
        $backup = array();
        if ($this->theme) {
            $template = $this->theme->template;
            $backup = array(
                $template->caching, $template->cache_lifetime
            );
        } else {
            $template = null;
            $template = new XoopsTpl();
        }
        $block_handler = $xoops->getHandlerBlock();
        $block_arr = $block_handler->getAllByGroupModule($groups, $mid, $isStart, XOOPS_BLOCK_VISIBLE);
        $xoops->preload()->triggerEvent('core.class.theme_blocks.retrieveBlocks', array(&$this, &$template, &$block_arr));
        foreach ($block_arr as $block) {
            /* @var $block XoopsBlock */
            $side = $oldzones[$block->getVar('side')];
            if ($var = $this->buildBlock($block, $template)) {
                $this->blocks[$side][$var["id"]] = $var;
            }
        }
        if ($this->theme) {
            list ($template->caching, $template->cache_lifetime) = $backup;
        }
    }

    /**
     * XoopsThemeBlocksPlugin::generateCacheId()
     *
     * @param string $cache_id
     * @return string
     */
    public function generateCacheId($cache_id)
    {
        if ($this->theme) {
            $cache_id = $this->theme->generateCacheId($cache_id);
        }
        return $cache_id;
    }

    /**
     * XoopsThemeBlocksPlugin::buildBlock()
     *
     * @param XoopsBlock $xobject
     * @param XoopsTpl $template
     * @return array|bool
     */
    public function buildBlock($xobject, &$template)
    {
        $xoops = Xoops::getInstance();
        // The lame type workaround will change
        // bid is added temporarily as workaround for specific block manipulation
        $dirname = $xobject->getVar('dirname');
        $block = array(
            'id' => $xobject->getVar('bid'), 'module' => $dirname, 'title' => $xobject->getVar('title'),
            'weight' => $xobject->getVar('weight'), 'lastmod' => $xobject->getVar('last_modified')
        );

        $bcachetime = intval($xobject->getVar('bcachetime'));
        if (empty($bcachetime)) {
            $template->caching = 0;
        } else {
            $template->caching = 2;
            $template->cache_lifetime = $bcachetime;
        }
        $template->setCompileId($dirname);
        $tplName = ($tplName = $xobject->getVar('template'))
                ? "block:{$dirname}/{$tplName}"
                : "module:system/system_block_dummy.tpl";
        //$tplName = str_replace('.html', '.tpl', $tplName);

        $cacheid = $this->generateCacheId('blk_' . $xobject->getVar('bid'));

        $xoops->preload()->triggerEvent('core.themeblocks.buildblock.start', array($xobject, $template->isCached($tplName, $cacheid)));

        if (!$bcachetime || !$template->isCached($tplName, $cacheid)) {

            //Get theme metas
            $old = array();
            if ($this->theme && $bcachetime) {
                foreach ($this->theme->metas as $type => $value) {
                    $old[$type] = $this->theme->metas[$type];
                }
            }

            //build block
            if ($bresult = $xobject->buildBlock()) {
                $template->assign('block', $bresult);
                $block['content'] = $template->fetch($tplName, $cacheid);
            } else {
                $block = false;
            }

            //check if theme added new metas
            if ($this->theme && $bcachetime) {
                $metas = array();
                foreach ($this->theme->metas as $type => $value) {
                    $dif = Xoops_Utils::arrayRecursiveDiff($this->theme->metas[$type], $old[$type]);
                    if (count($dif)) {
                        $metas[$type] = $dif;
                    }
                }
                if (count($metas)) {
                    Xoops_Cache::write($cacheid, $metas);
                }
            }
        } else {
            $block['content'] = $template->fetch($tplName, $cacheid);
        }

        //add block cached metas
        if ($this->theme && $bcachetime) {
            if ($metas = Xoops_Cache::read($cacheid)) {
                foreach ($metas as $type => $value) {
                    $this->theme->metas[$type] = array_merge($this->theme->metas[$type], $metas[$type]);
                }
            }
        }
        $template->setCompileId();
        return $block;
    }
}
