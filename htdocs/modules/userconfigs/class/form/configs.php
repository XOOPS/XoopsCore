<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @author          trabis <lusopoemas@gmail.com>
 * @package         userconfigs
 * @version         $Id$
 */

class UserconfigsConfigsForm extends Xoops\Form\SimpleForm
{
    /**
     * __construct
     *
     * @param null $obj
     */
    public function __construct($obj = null)
    {
    }

    /**
     * @param array       $obj
     * @param XoopsModule $mod
     */
    public function getForm(&$obj, XoopsModule $mod)
    {
        $xoops = Xoops::getInstance();
        $helper = Userconfigs::getInstance();
        $config_handler = $helper->getHandlerConfig();
        /* @var $plugin UserconfigsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($mod->getVar('dirname'), 'userconfigs')) {

            parent::__construct('', 'pref_form', 'index.php', 'post', true);
            if ($mod->getVar('dirname') != 'system') {
                $xoops->loadLanguage('modinfo', $mod->getVar('dirname'));
                $xoops->loadLocale($mod->getVar('dirname'));
            }
            $configs = $plugin->configs();
            $configNames = array();
            foreach (array_keys($configs) as $i) {
                $configNames[$configs[$i]['name']] =& $configs[$i];
            }
            $configCats = $plugin->categories();
            if (!$configCats) {
                $configCats = array(
                    'default' => array(
                        'name'        => _MD_USERCONFIGS_CONFIGS,
                        'description' => ''
                    )
                );
            }

            if (!in_array('default', array_keys($configCats))) {
                $configCats['default'] = array(
                    'name'        => _MD_USERCONFIGS_CONFIGS,
                    'description' => ''
                );
            }

            foreach (array_keys($configNames) as $name) {
                if (!isset($configNames[$name]['category'])) {
                    $configNames[$name]['category'] = 'default';
                }
            }

            $tabtray = new Xoops\Form\TabTray('', 'pref_tabtay', $xoops->getModuleConfig('jquery_theme', 'system'));
            $tabs = array();
            foreach ($configCats as $name => $info) {
                $tabs[$name] = new Xoops\Form\Tab($info['name'], 'pref_tab_' . $name);
                if (isset($info['description']) && $info['description'] != '') {
                    $tabs[$name]->addElement(new Xoops\Form\Label('', $info['description']));
                }
            }
            $count = count($obj);
            for ($i = 0; $i < $count; ++$i) {
                $title = Xoops_Locale::translate($obj[$i]->getVar('conf_title'), $mod->getVar('dirname'));
                $desc = ($obj[$i]->getVar('conf_desc') != '') ? Xoops_Locale::translate($obj[$i]->getVar('conf_desc'), $mod->getVar('dirname')) : '';
                switch ($obj[$i]->getVar('conf_formtype')) {

                    case 'textarea':
                        $myts = MyTextSanitizer::getInstance();
                        if ($obj[$i]->getVar('conf_valuetype') == 'array') {
                            // this is exceptional.. only when value type is arrayneed a smarter way for this
                            $ele = ($obj[$i]->getVar('conf_value') != '') ? new Xoops\Form\TextArea($title, $obj[$i]->getVar('conf_name'), $myts->htmlSpecialChars(implode('|', $obj[$i]->getConfValueForOutput())), 5, 5) : new Xoops\Form\TextArea($title, $obj[$i]->getVar('conf_name'), '', 5, 5);
                        } else {
                            $ele = new Xoops\Form\TextArea($title, $obj[$i]->getVar('conf_name'), $myts->htmlSpecialChars($obj[$i]->getConfValueForOutput()), 5, 5);
                        }
                        break;

                    case 'select':
                        $ele = new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        $options = $config_handler->getConfigOptions(new Criteria('conf_id', $obj[$i]->getVar('conf_id')));
                        $opcount = count($options);
                        for ($j = 0; $j < $opcount; ++$j) {
                            $optval = Xoops_Locale::translate($options[$j]->getVar('confop_value'), $mod->getVar('dirname'));
                            $optkey = Xoops_Locale::translate($options[$j]->getVar('confop_name'), $mod->getVar('dirname'));
                            $ele->addOption($optval, $optkey);
                        }
                        break;

                    case 'select_multi':
                        $ele = new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput(), 5, true);
                        $options = $config_handler->getConfigOptions(new Criteria('conf_id', $obj[$i]->getVar('conf_id')));
                        $opcount = count($options);
                        for ($j = 0; $j < $opcount; ++$j) {
                            $optval = Xoops_Locale::translate($options[$j]->getVar('confop_value'), $mod->getVar('dirname'));
                            $optkey = Xoops_Locale::translate($options[$j]->getVar('confop_name'), $mod->getVar('dirname'));
                            $ele->addOption($optval, $optkey);
                        }
                        break;

                    case 'yesno':
                        $ele = new Xoops\Form\RadioYesNo($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        break;

                    case 'theme':
                    case 'theme_multi':
                        $ele = ($obj[$i]->getVar('conf_formtype') != 'theme_multi') ? new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput()) : new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput(), 5, true);
                        $dirlist = XoopsLists::getThemesList();
                        if (!empty($dirlist)) {
                            asort($dirlist);
                            $ele->addOptionArray($dirlist);
                        }
                        break;
                    case 'tplset':
                        $ele = new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        $tplset_handler = $xoops->getHandlerTplset();
                        $tplsetlist = $tplset_handler->getNameList();
                        asort($tplsetlist);
                        foreach ($tplsetlist as $key => $name) {
                            $ele->addOption($key, $name);
                        }
                        break;

                    case 'cpanel':
                        $ele = new Xoops\Form\Hidden($obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        /*
                        $ele = new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                        XoopsLoad::load("cpanel", "system");
                        $list = XoopsSystemCpanel::getGuis();
                        $ele->addOptionArray($list);  */
                        break;

                    case 'timezone':
                        $ele = new Xoops\Form\SelectTimeZone($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        break;

                    case 'language':
                        $ele = new Xoops\Form\SelectLanguage($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        break;

                    case 'locale':
                        $ele = new Xoops\Form\SelectLocale($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        break;

                    case 'startpage':
                        $ele = new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());

                        $module_handler = $xoops->getHandlerModule();
                        $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
                        $criteria->add(new Criteria('isactive', 1));
                        $moduleslist = $module_handler->getNameList($criteria, true);
                        $moduleslist['--'] = XoopsLocale::NONE;
                        $ele->addOptionArray($moduleslist);
                        break;

                    case 'group':
                        $ele = new Xoops\Form\SelectGroup($title, $obj[$i]->getVar('conf_name'), false, $obj[$i]->getConfValueForOutput(), 1, false);
                        break;

                    case 'group_multi':
                        $ele = new Xoops\Form\SelectGroup($title, $obj[$i]->getVar('conf_name'), false, $obj[$i]->getConfValueForOutput(), 5, true);
                        break;

                    // RMV-NOTIFY: added 'user' and 'user_multi'
                    case 'user':
                        $ele = new Xoops\Form\SelectUser($title, $obj[$i]->getVar('conf_name'), false, $obj[$i]->getConfValueForOutput(), 1, false);
                        break;

                    case 'user_multi':
                        $ele = new Xoops\Form\SelectUser($title, $obj[$i]->getVar('conf_name'), false, $obj[$i]->getConfValueForOutput(), 5, true);
                        break;
                    case 'module_cache':
                        $module_handler = $xoops->getHandlerModule();
                        $modules = $module_handler->getObjectsArray(new Criteria('hasmain', 1), true);
                        $currrent_val = $obj[$i]->getConfValueForOutput();
                        $cache_options = array(
                            '0'       => XoopsLocale::NO_CACHE,
                            '30'      => sprintf(XoopsLocale::F_SECONDS, 30),
                            '60'      => XoopsLocale::ONE_MINUTE,
                            '300'     => sprintf(XoopsLocale::F_MINUTES, 5),
                            '1800'    => sprintf(XoopsLocale::F_MINUTES, 30),
                            '3600'    => XoopsLocale::ONE_HOUR,
                            '18000'   => sprintf(XoopsLocale::F_HOURS, 5),
                            '86400'   => XoopsLocale::ONE_DAY,
                            '259200'  => sprintf(XoopsLocale::F_DAYS, 3),
                            '604800'  => XoopsLocale::ONE_WEEK,
                            '2592000' => XoopsLocale::ONE_MONTH
                        );
                        if (count($modules) > 0) {
                            $ele = new Xoops\Form\ElementTray($title, '<br />');
                            foreach (array_keys($modules) as $mid) {
                                $c_val = isset($currrent_val[$mid]) ? (int)($currrent_val[$mid]) : null;
                                $selform = new Xoops\Form\Select($modules[$mid]->getVar('name'), $obj[$i]->getVar('conf_name') . "[$mid]", $c_val);
                                $selform->addOptionArray($cache_options);
                                $ele->addElement($selform);
                                unset($selform);
                            }
                        } else {
                            $ele = new Xoops\Form\Label($title, SystemLocale::NO_MODULE_TO_CACHE);
                        }
                        break;

                    case 'site_cache':
                        $ele = new Xoops\Form\Select($title, $obj[$i]->getVar('conf_name'), $obj[$i]->getConfValueForOutput());
                        $ele->addOptionArray(array(
                            '0'       => XoopsLocale::NO_CACHE,
                            '30'      => sprintf(XoopsLocale::F_SECONDS, 30),
                            '60'      => XoopsLocale::ONE_MINUTE,
                            '300'     => sprintf(XoopsLocale::F_MINUTES, 5),
                            '1800'    => sprintf(XoopsLocale::F_MINUTES, 30),
                            '3600'    => XoopsLocale::ONE_HOUR,
                            '18000'   => sprintf(XoopsLocale::F_HOURS, 5),
                            '86400'   => XoopsLocale::ONE_DAY,
                            '259200'  => sprintf(XoopsLocale::F_DAYS, 3),
                            '604800'  => XoopsLocale::ONE_WEEK,
                            '2592000' => XoopsLocale::ONE_MONTH
                        ));
                        break;

                    case 'password':
                        $myts = MyTextSanitizer::getInstance();
                        $ele = new Xoops\Form\Password($title, $obj[$i]->getVar('conf_name'), 5, 255, $myts->htmlSpecialChars($obj[$i]->getConfValueForOutput()));
                        break;

                    case 'color':
                        $myts = MyTextSanitizer::getInstance();
                        $ele = new Xoops\Form\ColorPicker($title, $obj[$i]->getVar('conf_name'), $myts->htmlSpecialChars($obj[$i]->getConfValueForOutput()));
                        break;

                    case 'hidden':
                        $myts = MyTextSanitizer::getInstance();
                        $ele = new Xoops\Form\Hidden($obj[$i]->getVar('conf_name'), $myts->htmlSpecialChars($obj[$i]->getConfValueForOutput()));
                        break;

                    case 'textbox':
                    default:
                        $myts = MyTextSanitizer::getInstance();
                        $ele = new Xoops\Form\Text($title, $obj[$i]->getVar('conf_name'), 5, 255, $myts->htmlSpecialChars($obj[$i]->getConfValueForOutput()));
                        break;
                }
                $hidden = new Xoops\Form\Hidden('conf_ids[]', $obj[$i]->getVar('conf_id'));
                if (isset($ele)) {
                    $ele->setDescription($desc);
                    if ($obj[$i]->getVar('conf_formtype') != 'hidden') {
                        $name = 'default';
                        if (isset($configNames[$obj[$i]->getVar('conf_name')]['category'])) {
                            $name = $configNames[$obj[$i]->getVar('conf_name')]['category'];
                        }
                        $tabs[$name]->addElement($ele);
                    } else {
                        $this->addElement($ele);
                    }
                    $this->addElement($hidden);
                    unset($ele);
                    unset($hidden);
                }
            }
            foreach (array_keys($tabs) as $name) {
                if ($tabs[$name]->getElements()) {
                    $tabtray->addElement($tabs[$name]);
                }
            }
            $this->addElement($tabtray);
            $this->addElement(new Xoops\Form\Hidden('op', 'save'));
            $this->addElement(new Xoops\Form\Hidden('mid', $mod->getVar('mid')));
            $this->addElement(new Xoops\Form\Button('', 'button', XoopsLocale::A_SUBMIT, 'submit'));
        }
    }
}
