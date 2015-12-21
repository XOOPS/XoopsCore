<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Form\ElementFactory;
use Xoops\Form\ThemeForm;

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

$form = new ThemeForm('Extended Form Element Definitions Example', 'example', '', 'post', true, 'horizontal');

// establish factory, and set it to add elements directly to our form by default
$factory = new ElementFactory();
$factory->setContainer($form);

$factory->create([
    ElementFactory::CLASS_KEY => 'Text',
    'caption' => 'Code',
    'name' => 'code',
    'value' => '',
    'placeholder' => 'Code...',
    'description' => 'Description code',
    'pattern' => '^.{3,}$',
    ':pattern_description' => 'You need at least 3 characters',
    'datalist' => ['Alfa', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot'],
    'required' => null,
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Text',
    'caption' => 'Password',
    'name' => 'password',
    'value' => '',
    'placeholder' => 'Enter Password',
    'description' => 'Description password',
    'pattern' => '^.{8,}$',
    ':pattern_description' => 'You need at least 8 characters',
    'autocomplete' => 'off',
    'required' => null,
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'TextArea',
    'caption' => 'Description',
    'name' => 'description',
    'value' => '',
    'rows' => 5,
    'cols' => 64,
    'maxlength' => 4096,
    'placeholder' => 'Enter the description',
    'description' => 'Your description',
    'required' => null,
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Radio',
    'caption' => 'Radio Inline',
    'name' => 'radio_inline',
    'value' => 1,
    'description' => 'Description of radio',
    'option' => [
        1 => 'Radio 1',
        2 => 'Radio 2',
        3 => 'Radio 3',
    ],
    ':inline' => true,
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Checkbox',
    'caption' => 'Checkbox',
    'name' => 'checkbox',
    'value' => [1, 3],
    'description' => 'Description of Checkbox',
    'option' => [
        1 => 'Checkbox 1',
        2 => 'Checkbox 2',
        3 => 'Checkbox 3',
    ],
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Select',
    'caption' => 'Select',
    'name' => 'select',
    'value' => 1,
    'description' => 'Description of Select',
    'option' => [
        1 => 'Select 1',
        2 => 'Select 2',
        3 => 'Select 3',
    ],
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Select',
    'caption' => 'Select Optgroup',
    'name' => 'select_optgroup',
    'value' => 1,
    'description' => 'Description of Select Optgroup',
    'option' => [
        'Switzerland' => [1 => 'Geneva', 2 => 'Bern', 3 => 'Zurich'],
        'France' => [4 => 'Paris', 5 => 'Lyon', 6 => 'Grenoble', 7 => 'Marseille'],
    ],
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'DateTime',
    'caption' => 'Date time',
    'name' => 'date_time',
    'description' => 'Description Date time',
    ':minuteinterval' => 30,
]);

$testTray = $factory->create([
    ElementFactory::CLASS_KEY => 'ElementTray',
    'caption' => 'Select Tray',
    ':joiner' => '',
]);

$factory->create([
    ElementFactory::FORM_KEY => $testTray, // override the default, and add to tray instead
    ElementFactory::CLASS_KEY => 'Select',
    'caption' => 'Category',
    'name' => 'select_cat',
    'size' => 5,
    'value' => 1,
    'option' => [
        1 => 'Category 1',
        2 => 'Category 2',
        3 => 'Category 3',
        4 => 'Category 4',
        5 => 'Category 5',
        6 => 'Category 6',
        7 => 'Category 7',
        8 => 'Category 8',
    ],
]);

$factory->create([
    ElementFactory::FORM_KEY => $testTray,
    ElementFactory::CLASS_KEY => 'Select',
    'caption' => 'Sub Cat',
    'name' => 'select_subcat',
    'size' => 5,
    'value' => [2,4,6],
    'option' => [
        1 => 'Sub 1',
        2 => 'Sub 2',
        3 => 'Sub 3',
        4 => 'Sub 4',
        5 => 'Sub 5',
        6 => 'Sub 6',
        7 => 'Sub 7',
        8 => 'Sub 8',
    ],
    'multiple' => null,
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Button',
    'caption' => '',
    'name' => 'submit_button',
    'type' => 'submit',
    'value' => XoopsLocale::A_SUBMIT,
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'Button',
    'caption' => '',
    'name' => 'reset_button',
    'type' => 'reset',
    'value' => XoopsLocale::A_RESET,
    'class' => 'btn btn-danger',
    'onclick' => 'return confirm("Are you sure?");',
]);

$factory->create([
    ElementFactory::CLASS_KEY => 'ButtonTray',
    'caption' => '',
    'name' => 'button_tray',
    'type' => 'submit',
    'class' => 'btn btn-inverse',
    'value' => XoopsLocale::A_SUBMIT,
    ':showdelete' => true,
]);


// example custom element class
class AwesomeButton extends Xoops\Form\Button
{
    /**
     * @param array $attributes array of all attributes
     */
    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->set('type', 'button');
        $this->setIfNotSet('name', 'awesome');
        $this->setIfNotSet('value', 'Awesome!');
        $this->add('class', 'btn btn-large btn-success');
    }
}

// use the custom element class
$factory->create([
    ElementFactory::CLASS_KEY => '\AwesomeButton', // Use fully qualified name for class
    'caption' => 'A custom form Element',
    'onclick' => 'alert("Awesome!");',
]);

$form->display();

\Xoops\Utils::dumpFile(__FILE__);
$xoops->footer();
