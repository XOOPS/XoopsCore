<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;

/**
 * Extended User Profile
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
$xoops = Xoops::getInstance();
$myts = MyTextSanitizer::getInstance();

$limit_default = 20;
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "search";
$groups = $xoops->user ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$searchable_types = array(
    'textbox', 'select', 'radio', 'yesno', 'date', 'datetime', 'timezone', 'language'
);

switch ($op) {
    default:
    case "search":
        $xoops->header('profile_search.tpl');
        $xoops->appendConfig('profile_breadcrumbs', array('caption' => XoopsLocale::A_SEARCH));
        $sortby_arr = array();

        // Dynamic fields
        /* @var $profile_handler ProfileProfileHandler */
        $profile_handler = $xoops->getModuleHandler('profile');
        // Get fields
        $fields = $profile_handler->loadFields();
        // Get ids of fields that can be searched
        $gperm_handler = $xoops->getHandlerGroupperm();
        $searchable_fields = $gperm_handler->getItemIds('profile_search', $groups, $xoops->module->getVar('mid'));

        $searchform = new Xoops\Form\ThemeForm("", "searchform", "search.php", "post");

        $name_tray = new Xoops\Form\ElementTray(XoopsLocale::USERNAME);
        $name_tray->addElement(new Xoops\Form\SelectMatchOption('', 'uname_match'));
        $name_tray->addElement(new Xoops\Form\Text('', 'uname', 35, 255));
        $searchform->addElement($name_tray);

        $email_tray = new Xoops\Form\ElementTray(XoopsLocale::EMAIL);
        $email_tray->addElement(new Xoops\Form\SelectMatchOption('', 'email_match'));
        $email_tray->addElement(new Xoops\Form\Text('', 'email', 35, 255));
        $searchform->addElement($email_tray);

        // add search groups , only for Webmasters
        if ($xoops->user && $xoops->user->isAdmin()) {
            $group_tray = new Xoops\Form\ElementTray(XoopsLocale::USER_GROUPS);
            $group_tray->addElement(new Xoops\Form\SelectGroup('', "selgroups", null, false, 5, true));
            $searchform->addElement($group_tray);
        }

        foreach (array_keys($fields) as $i) {
            if (!in_array($fields[$i]->getVar('field_id'), $searchable_fields) || !in_array($fields[$i]->getVar('field_type'), $searchable_types)) {
                continue;
            }
            $sortby_arr[$i] = $fields[$i]->getVar('field_title');
            switch ($fields[$i]->getVar('field_type')) {
                case "textbox":
                    if ($fields[$i]->getVar('field_valuetype') == XOBJ_DTYPE_INT) {
                        $searchform->addElement(new Xoops\Form\Text(sprintf(_PROFILE_MA_LARGERTHAN, $fields[$i]->getVar('field_title')), $fields[$i]->getVar('field_name') . "_larger", 35, 35));
                        $searchform->addElement(new Xoops\Form\Text(sprintf(_PROFILE_MA_SMALLERTHAN, $fields[$i]->getVar('field_title')), $fields[$i]->getVar('field_name') . "_smaller", 35, 35));
                    } else {
                        $tray = new Xoops\Form\ElementTray($fields[$i]->getVar('field_title'));
                        $tray->addElement(new Xoops\Form\SelectMatchOption('', $fields[$i]->getVar('field_name') . "_match"));
                        $tray->addElement(new Xoops\Form\Text('', $fields[$i]->getVar('field_name'), 35, $fields[$i]->getVar('field_maxlength')));
                        $searchform->addElement($tray);
                        unset($tray);
                    }
                    break;

                case "radio":
                case "select":
                    $options = $fields[$i]->getVar('field_options');
                    $size = MIN(count($options), 10);
                    $element = new Xoops\Form\Select($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, $size, true);
                    asort($options);
                    $element->addOptionArray($options);
                    $searchform->addElement($element);
                    unset($element);
                    break;

                case "yesno":
                    $element = new Xoops\Form\Select($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, 2, true);
                    $element->addOption(1, XoopsLocale::YES);
                    $element->addOption(0, XoopsLocale::NO);
                    $searchform->addElement($element);
                    unset($element);
                    break;

                case "date":
                case "datetime":
                    $searchform->addElement(new Xoops\Form\DateSelect(sprintf(_PROFILE_MA_LATERTHAN, $fields[$i]->getVar('field_title')), $fields[$i]->getVar('field_name') . "_larger", 15, ''));
                    $searchform->addElement(new Xoops\Form\DateSelect(sprintf(_PROFILE_MA_EARLIERTHAN, $fields[$i]->getVar('field_title')), $fields[$i]->getVar('field_name') . "_smaller", 15, ''));
                    break;

                case "timezone":
                    $element = new Xoops\Form\Select($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, 6, true);
                    $element->addOptionArray(XoopsLists::getTimeZoneList());
                    $searchform->addElement($element);
                    unset($element);
                    break;

                case "language":
                    $element = new Xoops\Form\SelectLanguage($fields[$i]->getVar('field_title'), $fields[$i]->getVar('field_name'), null, 6);
                    $searchform->addElement($element);
                    unset($element);
                    break;
            }
        }
        asort($sortby_arr);
        $sortby_arr = array_merge(array("" => XoopsLocale::NONE, "uname" => XoopsLocale::USERNAME, "email" => XoopsLocale::EMAIL), $sortby_arr);
        $sortby_select = new Xoops\Form\Select(_PROFILE_MA_SORTBY, 'sortby');
        $sortby_select->addOptionArray($sortby_arr);
        $searchform->addElement($sortby_select);

        $order_select = new Xoops\Form\Radio(_PROFILE_MA_ORDER, 'order', 0);
        $order_select->addOption(0, XoopsLocale::ASCENDING_ORDER);
        $order_select->addOption(1, XoopsLocale::DESCENDING_ORDER);
        $searchform->addElement($order_select);

        $limit_text = new Xoops\Form\Text(_PROFILE_MA_PERPAGE, 'limit', 15, 10, $limit_default);
        $searchform->addElement($limit_text);
        $searchform->addElement(new Xoops\Form\Hidden('op', 'results'));
        $searchform->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));

        $searchform->assign($xoops->tpl());
        $xoops->tpl()->assign('page_title', _PROFILE_MA_SEARCH);

        //added count user
        $member_handler = $xoops->getHandlerMember();
        $acttotal = $member_handler->getUserCount(new Criteria('level', 0, '>'));
        $total = sprintf(_PROFILE_MA_ACTUS, "<span style='color:#ff0000;'>{$acttotal}</span>");
        $xoops->tpl()->assign('total_users', $total);
        break;

    case "results":
        $xoops->header('module:profile/profile_results.tpl');
        $xoops->tpl()->assign('page_title', _PROFILE_MA_RESULTS);

        $xoops->appendConfig('profile_breadcrumbs', array(
                                                         'caption' => XoopsLocale::A_SEARCH,
                                                         'link' => $xoops->url('modules/profile/search.php'),
                                                    ));
        $xoops->appendConfig('profile_breadcrumbs', array('caption' => _PROFILE_MA_RESULTS));

        $member_handler = $xoops->getHandlerMember();
        // Dynamic fields
        /* @var $profile_handler ProfileProfileHandler */
        $profile_handler = $xoops->getModuleHandler('profile');
        // Get fields
        $fields = $profile_handler->loadFields();
        // Get ids of fields that can be searched
        $gperm_handler = $xoops->getHandlerGroupperm();
        $searchable_fields = $gperm_handler->getItemIds('profile_search', $groups, $xoops->module->getVar('mid'));
        $searchvars = array();
        $search_url = array();

        $criteria = new CriteriaCompo(new Criteria('level', 0, '>'));
        if (isset($_REQUEST['uname']) && $_REQUEST['uname'] != '') {
            $string = $myts->addSlashes(trim($_REQUEST['uname']));
            switch ($_REQUEST['uname_match']) {
                case XOOPS_MATCH_START:
                    $string .= "%";
                    break;

                case XOOPS_MATCH_END:
                    $string = "%" . $string;
                    break;

                case XOOPS_MATCH_CONTAIN:
                    $string = "%" . $string . "%";
                    break;
            }
            $criteria->add(new Criteria('uname', $string, "LIKE"));
            $search_url[] = "uname=" . $_REQUEST['uname'];
            $search_url[] = "uname_match=" . $_REQUEST['uname_match'];
            $searchvars[] = "uname";
        }
        if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
            $string = $myts->addSlashes(trim($_REQUEST['email']));
            switch ($_REQUEST['email_match']) {
                case XOOPS_MATCH_START:
                    $string .= "%";
                    break;

                case XOOPS_MATCH_END:
                    $string = "%" . $string;
                    break;

                case XOOPS_MATCH_CONTAIN:
                    $string = "%" . $string . "%";
                    break;
            }
            $searchvars[] = "email";
            $search_url[] = "email=" . $_REQUEST['email'];
            $search_url[] = "email_match=" . $_REQUEST['email_match'];
            $criteria->add(new Criteria('email', $string, "LIKE"));
            $criteria->add(new Criteria('user_viewemail', 1));
        }

        //$search_url = array();
        foreach (array_keys($fields) as $i) {
            //Radio and Select fields
            if (!in_array($fields[$i]->getVar('field_id'), $searchable_fields) || !in_array($fields[$i]->getVar('field_type'), $searchable_types)) {
                continue;
            }
            $fieldname = $fields[$i]->getVar('field_name');
            if (in_array($fields[$i]->getVar('field_type'), array("select", "radio"))) {
                if (empty($_REQUEST[$fieldname])) {
                    continue;
                }
                //If field value is sent through request and is not an empty value
                switch ($fields[$i]->getVar('field_valuetype')) {
                    case XOBJ_DTYPE_OTHER:
                    case XOBJ_DTYPE_INT:
                        $value = array_map('intval', $_REQUEST[$fieldname]);
                        $searchvars[] = $fieldname;
                        $criteria->add(new Criteria($fieldname, "(" . implode(',', $value) . ")", "IN"));
                        break;

                    case XOBJ_DTYPE_URL:
                    case XOBJ_DTYPE_TXTBOX:
                    case XOBJ_DTYPE_TXTAREA:
                        $value = array_map(array($xoopsDB, "quoteString"), $_REQUEST[$fieldname]);
                        $searchvars[] = $fieldname;
                        $criteria->add(new Criteria($fieldname, "(" . implode(',', $value) . ")", "IN"));
                        break;
                }
                foreach ($_REQUEST[$fieldname] as $value) {
                    $search_url[] = $fieldname . "[]=" . $value;
                }
            } else {
                //Other fields (not radio, not select)
                switch ($fields[$i]->getVar('field_valuetype')) {
                    case XOBJ_DTYPE_OTHER:
                    case XOBJ_DTYPE_INT:
                        switch ($fields[$i]->getVar('field_type')) {
                            case "date":
                            case "datetime":
                                $value = $_REQUEST[$fieldname . "_larger"];
                                if (!($value = strtotime($_REQUEST[$fieldname . "_larger"]))) {
                                    $value = intval($_REQUEST[$fieldname . "_larger"]);
                                }
                                if ($value > 0) {
                                    $search_url[] = $fieldname . "_larger=" . $value;
                                    $searchvars[] = $fieldname;
                                    $criteria->add(new Criteria($fieldname, $value, ">="));
                                }

                                $value = $_REQUEST[$fieldname . "_smaller"];
                                if (!($value = strtotime($_REQUEST[$fieldname . "_smaller"]))) {
                                    $value = intval($_REQUEST[$fieldname . "_smaller"]);
                                }
                                if ($value > 0) {
                                    $search_url[] = $fieldname . "_smaller=" . $value;
                                    $searchvars[] = $fieldname;
                                    $criteria->add(new Criteria($fieldname, $value + 24 * 3600, "<="));
                                }
                                break;

                            default:
                                if (isset($_REQUEST[$fieldname . "_larger"]) && intval($_REQUEST[$fieldname . "_larger"]) != 0) {
                                    $value = intval($_REQUEST[$fieldname . "_larger"]);
                                    $search_url[] = $fieldname . "_larger=" . $value;
                                    $searchvars[] = $fieldname;
                                    $criteria->add(new Criteria($fieldname, $value, ">="));
                                }

                                if (isset($_REQUEST[$fieldname . "_smaller"]) && intval($_REQUEST[$fieldname . "_smaller"]) != 0) {
                                    $value = intval($_REQUEST[$fieldname . "_smaller"]);
                                    $search_url[] = $fieldname . "_smaller=" . $value;
                                    $searchvars[] = $fieldname;
                                    $criteria->add(new Criteria($fieldname, $value, "<="));
                                }
                                break;
                        }

                        if (isset($_REQUEST[$fieldname]) && !isset($_REQUEST[$fieldname . "_smaller"]) && !isset($_REQUEST[$fieldname . "_larger"])) {
                            if (!is_array($_REQUEST[$fieldname])) {
                                $value = intval($_REQUEST[$fieldname]);
                                $search_url[] = $fieldname . "=" . $value;
                                $criteria->add(new Criteria($fieldname, $value, "="));
                            } else {
                                $value = array_map("intval", $_REQUEST[$fieldname]);
                                foreach ($value as $thisvalue) {
                                    $search_url[] = $fieldname . "[]=" . $thisvalue;
                                }
                                $criteria->add(new Criteria($fieldname, "(" . implode(',', $value) . ")", "IN"));
                            }

                            $searchvars[] = $fieldname;
                        }
                        break;

                    case XOBJ_DTYPE_URL:
                    case XOBJ_DTYPE_TXTBOX:
                    case XOBJ_DTYPE_TXTAREA:
                        if (isset($_REQUEST[$fieldname]) && $_REQUEST[$fieldname] != "") {
                            $value = $myts->addSlashes(trim($_REQUEST[$fieldname]));
                            switch ($_REQUEST[$fieldname . '_match']) {
                                case XOOPS_MATCH_START:
                                    $value .= "%";
                                    break;

                                case XOOPS_MATCH_END:
                                    $value = "%" . $value;
                                    break;

                                case XOOPS_MATCH_CONTAIN:
                                    $value = "%" . $value . "%";
                                    break;
                            }
                            $search_url[] = $fieldname . "=" . $_REQUEST[$fieldname];
                            $search_url[] = $fieldname . "_match=" . $_REQUEST[$fieldname . '_match'];
                            $operator = "LIKE";
                            $criteria->add(new Criteria($fieldname, $value, $operator));
                            $searchvars[] = $fieldname;
                        }
                        break;
                }
            }
        }

        $sortby = 'uname';
        if (!empty($_REQUEST['sortby'])) {
            switch ($_REQUEST['sortby']) {
                case 'name':
                case 'email':
                case 'uname':
                    $sortby = $_REQUEST['sortby'];
                    break;
                default:
                    if (isset($fields[$_REQUEST['sortby']])) {
                        $sortby = $fields[$_REQUEST['sortby']]->getVar('field_name');
                    }
                    break;
            }
            $criteria->setSort($sortby);
        }

        // add search groups , only for Webmasters
        $searchgroups = array();
        if ($xoops->isAdmin()) {
            $searchgroups = empty($_REQUEST['selgroups']) ? array() : array_map("intval", $_REQUEST['selgroups']);
            foreach ($searchgroups as $group) {
                $search_url[] = 'selgroups[]=' . $group;
            }
        }

        $order = $_REQUEST['order'] == 0 ? "ASC" : "DESC";
        $criteria->setOrder($order);

        $limit = empty($_REQUEST['limit']) ? $limit_default : intval($_REQUEST['limit']);
        $criteria->setLimit($limit);

        $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
        $criteria->setStart($start);

        list($users, $profiles, $total_users) = $profile_handler->search($criteria, $searchvars, $searchgroups);

        $total = sprintf(_PROFILE_MA_FOUNDUSER, "<span class='red'>{$total_users}</span>") . " ";
        $xoops->tpl()->assign('total_users', $total);

        //Sort information
        foreach (array_keys($users) as $k) {
            $userarray = array();
            $userarray["output"][] = "<a href='userinfo.php?uid=" . $users[$k]->getVar('uid') . "' title=''>" . $users[$k]->getVar('uname') . "</a>";
            $userarray["output"][] = ($users[$k]->getVar('user_viewemail') == 1 || $xoops->user->isAdmin())
                    ? $users[$k]->getVar('email') : "";

            foreach (array_keys($fields) as $i) {
                if (in_array($fields[$i]->getVar('field_id'), $searchable_fields) && in_array($fields[$i]->getVar('field_type'), $searchable_types) && in_array($fields[$i]->getVar('field_name'), $searchvars)) {
                    $userarray["output"][] = $fields[$i]->getOutputValue($users[$k], $profiles[$k]);
                }
            }
            $xoops->tpl()->append('users', $userarray);
            unset($userarray);
        }

        //Get captions
        $captions[] = XoopsLocale::USERNAME;
        $captions[] = XoopsLocale::EMAIL;
        foreach (array_keys($fields) as $i) {
            if (in_array($fields[$i]->getVar('field_id'), $searchable_fields) && in_array($fields[$i]->getVar('field_type'), $searchable_types) && in_array($fields[$i]->getVar('field_name'), $searchvars)) {
                $captions[] = $fields[$i]->getVar('field_title');
            }
        }
        $xoops->tpl()->assign('captions', $captions);

        if ($total_users > $limit) {
            $search_url[] = "op=results";
            $search_url[] = "order=" . $order;
            $search_url[] = "sortby=" . htmlspecialchars($sortby);
            $search_url[] = "limit=" . $limit;
            if (isset($search_url)) {
                $args = implode("&amp;", $search_url);
            }
            $nav = new XoopsPageNav($total_users, $limit, $start, "start", $args);
            $xoops->tpl()->assign('nav', $nav->renderNav(5));
        }
        break;
}
include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
