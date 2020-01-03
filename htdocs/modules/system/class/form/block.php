<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Kernel\Handlers\XoopsBlock;

/**
 * Blocks Form Class
 *
 * @category  Modules/system/class/form
 * @package   SystemBlockForm
 * @author    Andricq Nicolas (AKA MusS)
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class SystemBlockForm extends Xoops\Form\ThemeForm
{
    /**
     * @var null|XoopsBlock $obj
     */
    private $obj = null;

    /**
     * __construct
     *
     * @param XoopsBlock $obj block object
     */
    public function __construct(XoopsBlock $obj)
    {
        $this->obj = $obj;
    }

    /**
     * getForm - get block edit form
     *
     * @param string $mode mode for form, edit or clone
     *
     * @return void
     */
    public function getForm($mode = 'edit')
    {
        $xoops = Xoops::getInstance();
        $xoops->loadLanguage('blocks', 'system');
        if ($this->obj->isNew()) {
            $title = SystemLocale::ADD_BLOCK;
            $modules = [-1];
            $groups = [FixedGroups::USERS, FixedGroups::ANONYMOUS, FixedGroups::ADMIN];
            $this->obj->setVar('block_type', XoopsBlock::BLOCK_TYPE_CUSTOM);
            $this->obj->setVar('visible', 1);
            $op = 'save';
        } else {
            $title = '';
            $modules = [];
            // Search modules
            $blockmodulelink_handler = $xoops->getHandlerBlockModuleLink();
            $criteria = new CriteriaCompo(new Criteria('block_id', $this->obj->getVar('bid')));
            $blockmodulelink = $blockmodulelink_handler->getObjects($criteria);
            /* @var $link XoopsBlockModuleLink */
            foreach ($blockmodulelink as $link) {
                $modules[] = $link->getVar('module_id');
            }
            // Search perms
            $groupperm_handler = $xoops->getHandlerGroupPermission();
            $groups = $groupperm_handler->getGroupIds('block_read', $this->obj->getVar('bid'));
            switch ($mode) {
                case 'edit':
                    $title = SystemLocale::EDIT_BLOCK;
                    break;
                case 'clone':
                    $title = SystemLocale::CLONE_BLOCK;
                    $this->obj->setVar('bid', 0);
                    if ($this->obj->isCustom()) {
                        $this->obj->setVar('block_type', XoopsBlock::BLOCK_TYPE_CUSTOM);
                    } else {
                        $this->obj->setVar('block_type', XoopsBlock::BLOCK_TYPE_CLONED);
                    }
                    break;
            }
            $op = 'save';
        }
        parent::__construct($title, 'blockform', 'admin.php', 'post', true);
        if (!$this->obj->isNew()) {
            $this->addElement(new Xoops\Form\Label(XoopsLocale::NAME, $this->obj->getVar('name')));
        }
        // Side position
        $side_select = new Xoops\Form\Select(XoopsLocale::SIDE, 'side', $this->obj->getVar('side'));
        $side_select->addOptionArray([
            0 => XoopsLocale::LEFT,
            1 => XoopsLocale::RIGHT,
            3 => SystemLocale::TOP_LEFT,
            4 => SystemLocale::TOP_RIGHT,
            5 => SystemLocale::TOP_CENTER,
            7 => SystemLocale::BOTTOM_LEFT,
            8 => SystemLocale::BOTTOM_RIGHT,
            9 => SystemLocale::BOTTOM_CENTER,
        ]);
        $this->addElement($side_select);
        // Order
        $weight = new Xoops\Form\Text(XoopsLocale::WEIGHT, 'weight', 1, 5, $this->obj->getVar('weight'), '');
        $weight->setPattern('^\d+$', XoopsLocale::E_YOU_NEED_A_POSITIVE_INTEGER);
        $this->addElement($weight, true);
        // Display
        $this->addElement(new Xoops\Form\RadioYesNo(XoopsLocale::VISIBLE, 'visible', $this->obj->getVar('visible')));
        // Visible In
        $mod_select = new Xoops\Form\Select(XoopsLocale::VISIBLE_IN, 'modules', $modules, 5, true);
        $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
        $criteria->add(new Criteria('isactive', 1));
        $module_list = $xoops->getHandlerModule()->getNameList($criteria);
        $module_list[-1] = XoopsLocale::TOP_PAGE;
        $module_list[0] = XoopsLocale::ALL_PAGES;
        ksort($module_list);
        $mod_select->addOptionArray($module_list);
        $this->addElement($mod_select);
        // Title
        $this->addElement(new Xoops\Form\Text(XoopsLocale::TITLE, 'title', 5, 255, $this->obj->getVar('title')), false);
        if ($this->obj->isNew() || $this->obj->isCustom()) {
            $editor_configs = [];
            $editor_configs['name'] = 'content_block';
            $editor_configs['value'] = $this->obj->getVar('content', 'e');
            $editor_configs['rows'] = 15;
            $editor_configs['cols'] = 6;
            $editor_configs['editor'] = $xoops->getModuleConfig('blocks_editor', 'system');
            $this->addElement(new Xoops\Form\Editor(XoopsLocale::CONTENT, 'content_block', $editor_configs), true);
            if (in_array($editor_configs['editor'], ['dhtmltextarea', 'textarea'])) {
                $ctype_select = new Xoops\Form\Select(SystemLocale::CONTENT_TYPE, 'c_type', $this->obj->getVar('c_type'));
                $ctype_select->addOptionArray([
                    XoopsBlock::CUSTOM_HTML => XoopsLocale::HTML,
                    XoopsBlock::CUSTOM_PHP => SystemLocale::PHP_SCRIPT,
                    XoopsBlock::CUSTOM_SMILIE => SystemLocale::AUTO_FORMAT_SMILIES_ENABLED,
                    XoopsBlock::CUSTOM_TEXT => SystemLocale::AUTO_FORMAT_SMILIES_DISABLED,
                ]);
                $this->addElement($ctype_select);
            } else {
                $this->addElement(new Xoops\Form\Hidden('c_type', XoopsBlock::CUSTOM_HTML));
            }
        } else {
            if ('' != $this->obj->getVar('template')) {
                $tplfile_handler = $xoops->getHandlerTplFile();
                $btemplate = $tplfile_handler->
                    find($xoops->getConfig('template_set'), 'block', $this->obj->getVar('bid'));
                if (count($btemplate) > 0) {
                    $this->addElement(new Xoops\Form\Label(
                        XoopsLocale::CONTENT,
                        '<a href="' . \XoopsBaseConfig::get('url') . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id='
                        . $btemplate[0]->getVar('tpl_id') . '">' . SystemLocale::EDIT_TEMPLATE . '</a>'
                    ));
                } else {
                    $btemplate2 = $tplfile_handler->find('default', 'block', $this->obj->getVar('bid'));
                    if (count($btemplate2) > 0) {
                        $this->addElement(new Xoops\Form\Label(
                            XoopsLocale::CONTENT,
                            '<a href="' . \XoopsBaseConfig::get('url') . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id='
                            . $btemplate2[0]->getVar('tpl_id') . '" rel="external">'
                            . SystemLocale::EDIT_TEMPLATE . '</a>'
                        ));
                    }
                }
            }
            if (false != $this->obj->getOptions()) {
                $this->addElement(new Xoops\Form\Label(XoopsLocale::OPTIONS, $this->obj->getOptions()));
            } else {
                $this->addElement(new Xoops\Form\Hidden('options', $this->obj->getVar('options')));
            }
            $this->addElement(new Xoops\Form\Hidden('c_type', XoopsBlock::CUSTOM_HTML));
        }
        $cache_select = new Xoops\Form\Select(
            SystemLocale::CACHE_LIFETIME,
            'bcachetime',
            $this->obj->getVar('bcachetime')
        );
        $cache_select->addOptionArray([
            '0' => XoopsLocale::NO_CACHE,
            '30' => sprintf(XoopsLocale::F_SECONDS, 30),
            '60' => XoopsLocale::ONE_MINUTE,
            '300' => sprintf(XoopsLocale::F_MINUTES, 5),
            '1800' => sprintf(XoopsLocale::F_MINUTES, 30),
            '3600' => XoopsLocale::ONE_HOUR,
            '18000' => sprintf(XoopsLocale::F_HOURS, 5),
            '86400' => XoopsLocale::ONE_DAY,
            '259200' => sprintf(XoopsLocale::F_DAYS, 3),
            '604800' => XoopsLocale::ONE_WEEK,
            '2592000' => XoopsLocale::ONE_MONTH,
        ]);
        $this->addElement($cache_select);
        // Groups
        $this->addElement(new Xoops\Form\SelectGroup(XoopsLocale::GROUPS, 'groups', true, $groups, 5, true));

        $this->addElement(new Xoops\Form\Hidden('block_type', $this->obj->getVar('block_type')));
        $this->addElement(new Xoops\Form\Hidden('mid', $this->obj->getVar('mid')));
        $this->addElement(new Xoops\Form\Hidden('func_num', $this->obj->getVar('func_num')));
        $this->addElement(new Xoops\Form\Hidden('func_file', $this->obj->getVar('func_file')));
        $this->addElement(new Xoops\Form\Hidden('show_func', $this->obj->getVar('show_func')));
        $this->addElement(new Xoops\Form\Hidden('edit_func', $this->obj->getVar('edit_func')));
        $this->addElement(new Xoops\Form\Hidden('template', $this->obj->getVar('template')));
        $this->addElement(new Xoops\Form\Hidden('dirname', $this->obj->getVar('dirname')));
        $this->addElement(new Xoops\Form\Hidden('name', $this->obj->getVar('name')));
        $this->addElement(new Xoops\Form\Hidden('bid', $this->obj->getVar('bid')));
        $this->addElement(new Xoops\Form\Hidden('op', $op));
        $this->addElement(new Xoops\Form\Hidden('fct', 'blocksadmin'));
        $buttonTray = new Xoops\Form\ElementTray('', '&nbsp;');
        if ($this->obj->isNew() || $this->obj->isCustom()) {
            $preview = new Xoops\Form\Button('', 'previewblock', XoopsLocale::A_PREVIEW, 'preview');
            $preview->setExtra('onclick="blocks_preview();"');
            $buttonTray->addElement($preview);
        }
        $buttonTray->addElement(new Xoops\Form\Button('', 'submitblock', XoopsLocale::A_SUBMIT, 'submit'));
        $this->addElement($buttonTray);
    }
}
