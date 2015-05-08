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
 * @copyright       2012-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL V2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

function menus_block_show($options)
{
    $block = array();
    $xoops = Xoops::getInstance();
    $helper = Menus::getInstance();

    /* @var $decorator MenusDecoratorInterface */
    $decorators = MenusDecorator::getAvailableDecorators();

    foreach ($decorators as $decorator) {
        $decorator->start();
    }

    $menu_id = $options[0];
    $criteria = new CriteriaCompo(new Criteria('mid', $menu_id));
    $criteria->setSort('weight');
    $criteria->setOrder('ASC');
    //get menus as an array with ids as keys
    $menus = $helper->getHandlerMenu()->getAll($criteria, null, false, false);
    unset($criteria);

    foreach ($menus as $key => $menu) {
        $hasAccess = true;
        foreach ($decorators as $decorator) {
            $decorator->hasAccess($menu, $hasAccess);
        }
        if (!$hasAccess) {
            unset($menus[$key]);
        }
    }

    $count = count($menus);
    if ($count == 0) {
        return $block;
    }

    foreach ($menus as $key => $menu) {
        foreach ($decorators as $decorator) {
            $decorator->decorateMenu($menu);
        }
        $menus[$key] = $menu;
    }

    foreach ($decorators as $decorator) {
        $decorator->end($menus);
    }

    $builder = new MenusBuilder($menus);
    $block = $builder->render();

    /*--------------------------------------------------------------*/
    //default files to load
    $css = array();
    $js = array();

    //get extra files from skins
    $skin = $options[1];
    $skin_info = $helper->getSkinInfo($skin, $options[2]);

    if (isset($skin_info['css'])) {
        $css = array_merge($css, $skin_info['css']);
    }

    if (isset($skin_info['js'])) {
        $js = array_merge($js, $skin_info['js']);
    }

    if ($helper->getConfig('assign_method') == 'xoopstpl') {
        $tpl_vars = '';
        foreach ($css as $file) {
            $tpl_vars .= "\n" . '<link rel="stylesheet" type="text/css" media="all" href="' . $file . '" />';
        }

        foreach ($js as $file) {
            $tpl_vars .= "\n" . '<script type="text/javascript" src="' . $file . '"></script>';
        }

        if (isset($skin_info['header'])) {
            $tpl_vars .= "\n" . $skin_info['header'];
        }

        $xoops->tpl()->assign('xoops_module_header', $tpl_vars . @$xoops->tpl()->getTemplateVars("xoops_module_header"));
    } else {
        foreach ($css as $file) {
            $xoops->theme()->addStylesheet($file);
        }

        foreach ($js as $file) {
            $xoops->theme()->addScript($file);
        }

        if (isset($skin_info['header'])) {
            $xoops->tpl()->assign('xoops_footer', @$xoops->tpl()->getTemplateVars("xoops_footer") . "\n" . $skin_info['header']);
        }
    }

    $blockTpl = new XoopsTpl();
    $blockTpl->assign('block', $block);
    $blockTpl->assign('config', $skin_info['config']);
    $blockTpl->assign('skinurl', $skin_info['url']);
    $blockTpl->assign('skinpath', $skin_info['path']);

    $block['content'] = $blockTpl->fetch($skin_info['template']);

    if ($options[3] == 'template') {
        $xoops->tpl()->assign('xoops_menu_' . $options[4], $block['content']);
        $block = array();
    }

    return $block;
}

function menus_block_edit($options)
{
    //Unique ID
    if (!$options[4] || (isset($_GET['op']) && $_GET['op'] == 'clone')) {
        $options[4] = uniqid();
    }

    $helper = Menus::getInstance();
    $helper->loadLanguage('admin');

    $criteria = new CriteriaCompo();
    $criteria->setSort('title');
    $criteria->setOrder('ASC');
    $menus = $helper->getHandlerMenus()->getList($criteria);
    unset($criteria);

    if (count($menus) == 0) {
        $form = "<a href='" . $helper->url('admin/admin_menus.php') . "'>" . _AM_MENUS_MSG_NOMENUS . "</a>";
        return $form;
    }

    //Menu
    $form = new Xoops\Form\BlockForm();
    $element = new Xoops\Form\Select(_MB_MENUS_SELECT_MENU, 'options[0]', $options[0], 1);
    $element->addOptionArray($menus);
    $element->setDescription(_MB_MENUS_SELECT_MENU_DSC);
    $form->addElement($element);

    //Skin
    $temp_skins = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . "/modules/menus/skins/", "");
    $skins_options = array();
    foreach ($temp_skins as $skin) {
        if (XoopsLoad::fileExists($helper->path('skins/' . $skin . '/skin_version.php'))) {
            $skins_options[$skin] = $skin;
        }
    }
    $element = new Xoops\Form\Select(_MB_MENUS_SELECT_SKIN, 'options[1]', $options[1], 1);
    $element->addOptionArray($skins_options);
    $element->setDescription(_MB_MENUS_SELECT_SKIN_DSC);
    $form->addElement($element);

    //Use skin from,theme
    $element = new Xoops\Form\RadioYesNo(_MB_MENUS_USE_THEME_SKIN, 'options[2]', $options[2]);
    $element->setDescription(_MB_MENUS_USE_THEME_SKIN_DSC);
    $form->addElement($element);

    //Display method
    $display_options = array(
        'block'    => _MB_MENUS_DISPLAY_METHOD_BLOCK,
        'template' => _MB_MENUS_DISPLAY_METHOD_TEMPLATE
    );
    $element = new Xoops\Form\Select(_MB_MENUS_DISPLAY_METHOD, 'options[3]', $options[3], 1);
    $element->addOptionArray($display_options);
    $element->setDescription(sprintf(_MB_MENUS_DISPLAY_METHOD_DSC, $options[4]));
    $form->addElement($element);

    //Unique ID
    $element = new Xoops\Form\Text(_MB_MENUS_UNIQUEID, 'options[4]', 2, 20, $options[4]);
    $element->setDescription(_MB_MENUS_UNIQUEID_DSC);
    $form->addElement($element);

    return $form->render();
}

function menus_mainmenu_show()
{
    $block = array();
    $xoops = Xoops::getInstance();
    $helper = Menus::getInstance();

    $module_handler = $xoops->getHandlerModule();
    $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
    $criteria->add(new Criteria('isactive', 1));
    $criteria->add(new Criteria('weight', 0, '>'));
    $modules = $module_handler->getObjectsArray($criteria, true);
    $moduleperm_handler = $xoops->getHandlerGroupperm();
    $groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);
    $menus = array();
    $menu = $helper->getHandlerMenu()->create();
    $menu->setVar('id', 1);
    $menu->setVar('pid', 0);
    $menu->setVar('alt_title', _MB_MENUS_HOME);
    $menu->setVar('title', _MB_MENUS_HOME);
    $menu->setVar('link', XOOPS_URL);
    $menu->setVar('image', 'icon-home');
    $menus[] = $menu->getValues();
    foreach (array_keys($modules) as $i) {
        if (in_array($i, $read_allowed)) {
            /* @var $plugin MenusPluginInterface */
            $menu = $helper->getHandlerMenu()->create();
            $menu->setVar('id', $i);
            $menu->setVar('pid', 0);
            $menu->setVar('title', $modules[$i]->getVar('name'));
            $menu->setVar('alt_title', $modules[$i]->getVar('name'));
            $menu->setVar('link', XOOPS_URL . '/modules/' . $modules[$i]->getVar('dirname'));
            $menu->setVar('image', 'icon-tags');
            $menus[] = $menu->getValues();
            if ($xoops->isModule() && $xoops->module->getVar('dirname') == $modules[$i]->getVar('dirname') && $plugin = \Xoops\Module\Plugin::getPlugin($modules[$i]->getVar('dirname'), 'menus')) {
                $sublinks = $plugin->subMenus();
                $j = -1;
                foreach ($sublinks as $sublink) {
                    $menu = $helper->getHandlerMenu()->create();
                    $menu->setVar('id', $j);
                    $menu->setVar('pid', $i);
                    $menu->setVar('title', $sublink['name']);
                    $menu->setVar('alt_title', $sublink['name']);
                    $menu->setVar('link', XOOPS_URL . '/modules/' . $modules[$i]->getVar('dirname') . '/'. $sublink['url']);
                    $menus[] = $menu->getValues();
                    $j--;
                }
            }
        }
    }
    $builder = new MenusBuilder($menus);
    $block = $builder->render();

    $skin_info = $helper->getSkinInfo('mainmenu', false);
    $blockTpl = new XoopsTpl();
    $blockTpl->assign('block', $block);
    $blockTpl->assign('config', $skin_info['config']);
    $blockTpl->assign('skinurl', $skin_info['url']);
    $blockTpl->assign('skinpath', $skin_info['path']);

    $block['content'] = $blockTpl->fetch($skin_info['template']);

    return $block;
}
