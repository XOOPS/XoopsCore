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
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

// We are using Simple Form, this way we can hide the names of the tabtray and form
$form = new Xoops\Form\SimpleForm('', 'form_id', 'form-tab.php');

//tabtray is the tabs holder,
//if you are using many tabtrays, or even nested tabtrays, you should use different ids
$tabTray = new Xoops\Form\TabTray('', 'uniqueid');

//Now we create our tabs
//Tabs should also have unique ids
//First tab
$tab1 = new Xoops\Form\Tab('Caption-1', 'tabid-1');
//Second tab
$tab2 = new Xoops\Form\Tab('Caption-2', 'tabid-2');
//Third tab
$tab3 = new Xoops\Form\Tab('Caption-3', 'tabid-3');

// Create the elements and assign them to the corresponding tabs
$code = new Xoops\Form\Text('Code', 'code', 2, 25, '', 'Code...');
$code->setDescription('Description code');
$code->setPattern('^.{3,}$', 'You need at least 3 characters');
$code->setDatalist(array('list 1','list 2','list 3'));
$tab1->addElement($code, true);

$password = new Xoops\Form\Password('Password', 'password', null, null, '', 'off', 'Enter Password');
$password->setDescription('Description password');
$password->setPattern('^.{8,}$', 'You need at least 8 characters');
$tab1->addElement($password, true);

$description = new Xoops\Form\TextArea('Description', 'description', '', 5, 6, 'Your description');
$description->setDescription('Put the description');
$tab1->addElement($description, true);

$description_2 = new Xoops\Form\DhtmlTextArea('Description_2', 'description_2', '', 6, 7);
$description_2->setDescription('Put the description_2');
$tab1->addElement($description_2, true);

$tab2->addElement(new Xoops\Form\RadioYesNo('Yes or No', 'yesorno', 0), false);

$radio_inline = new Xoops\Form\Radio('Radio Inline', 'radio_inline', 1, true);
$radio_inline->addOption(1, 'Radio Inline 1');
$radio_inline->addOption(2, 'Radio Inline 2');
$radio_inline->addOption(3, 'Radio Inline 3');
$radio_inline->setDescription('Description Radio Inline');
$tab2->addElement($radio_inline, false);

$radio = new Xoops\Form\Radio('Radio', 'radio', 2, false);
$radio->addOption(1, 'Radio 1');
$radio->addOption(2, 'Radio 2');
$radio->addOption(3, 'Radio 3');
$radio->setDescription('Description Radio');
$tab2->addElement($radio, false);

$checkbox_inline = new Xoops\Form\Checkbox('Checkbox Inline', 'checkbox_inline', 1, true);
$checkbox_inline->addOption(1, 'Checkbox Inline 1');
$checkbox_inline->addOption(2, 'Checkbox Inline 2');
$checkbox_inline->addOption(3, 'Checkbox Inline 3');
$checkbox_inline->setDescription('Description Checkbox Inline');
$tab3->addElement($checkbox_inline, true);

$checkbox = new Xoops\Form\Checkbox('Checkbox', 'checkbox', 2, false);
$checkbox->addOption(1, 'Checkbox 1');
$checkbox->addOption(2, 'Checkbox 2');
$checkbox->addOption(3, 'Checkbox 3');
$checkbox->setDescription('Description Checkbox');
$tab3->addElement($checkbox, true);

$label= new Xoops\Form\Label('Label', 'label', 'label');
$label->setDescription('Description Label');
$tab3->addElement($label, true);


$color = new Xoops\Form\ColorPicker('Color', 'color');
$color->setDescription('Description Color');
$tab3->addElement($color, true);

$file = new Xoops\Form\File('File', 'file');
$file->setDescription('Description File');
$tab3->addElement($file, true);

$select = new Xoops\Form\Select('Select', 'select', '', 1, false);
$select->addOption(1, 'Select 1');
$select->addOption(2, 'Select 2');
$select->addOption(3, 'Select 3');
$select->setDescription('Description Select');
$select->setClass('span2');
$tab3->addElement($select, true);

$select_optgroup = new Xoops\Form\Select('Select Optgroup', 'select_optgroup', '', 1, false);
$select_optgroup->addOptionGroup('Swiss', array(1 => 'Geneva', 2 => 'Bern', 3 => 'Zurich'));
$select_optgroup->addOptionGroup('France', array(4 => 'Paris', 5 => 'Lyon', 6 => 'Grenoble', 7 => 'Marseille'));
$select_optgroup->setDescription('Description Select Optgroup');
$select_optgroup->setClass('span3');
$tab3->addElement($select_optgroup, true);

$date = new Xoops\Form\DateSelect('Date', 'date', 0);
$date->setDescription('Description Date');
$tab3->addElement($date, true);

$date_time = new Xoops\Form\DateTimeSelect('Date time', 'date_time', 0);
$date_time->setDescription('Description Date time');
$tab3->addElement($date_time, true);

$tab3->addElement(new Xoops\Form\Captcha('Captcha', 'captcha', false), true);

$testtray = new Xoops\Form\ElementTray('Test tray');
$select_tray = new Xoops\Form\Select('Select_tray', 'select_tray', '', 4, true);
$select_tray->addOption(1, 'Select_tray 1');
$select_tray->addOption(2, 'Select_tray 2');
$select_tray->addOption(3, 'Select_tray 3');
$select_tray->addOption(4, 'Select_tray 4');
$select_tray->addOption(5, 'Select_tray 5');
$select_tray->addOption(6, 'Select_tray 6');
$select_tray->setDescription('Description Select_tray');
$select_tray->setClass('span2');
$testtray ->addElement($select_tray, true);
$testtray ->addElement($select_tray);
$tab3->addElement($testtray);


//Now we can assign the tabs to our tab tray
$tabTray->addElement($tab1);
$tabTray->addElement($tab2);
$tabTray->addElement($tab3);
//Now we can assign our Tabtray to the form
$form->addElement($tabTray);

//We want to keep our buttons out of the Tabtray so wee add them directly to the form
$buttonSubmit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($buttonSubmit);

$buttonReset = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
$buttonReset->setClass('btn btn-danger');
$form->addElement($buttonReset);

$buttonTray = new Xoops\Form\ButtonTray('button_tray', XoopsLocale::A_SUBMIT, 'submit', '', true);
$buttonTray->setClass('btn btn-warning');
$form->addElement($buttonTray);

//We are good, display the form!
$form->display();

\Xoops\Utils::dumpFile(__FILE__);
$xoops->footer();
