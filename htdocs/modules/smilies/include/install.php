<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Doctrine\DBAL\ParameterType;

/**
 * smilies module - install supplement for smilies module
 *
 * @copyright 2015-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package   smilies
 * @since     2.6.0
 * @author    Richard Griffith <richard@geekwright.com>
 */

/**
 * xoops_module_install_smilies - install supplement for smilies module
 *
 * @param object &$module module object
 *
 * @return boolean true on success
 */
function xoops_module_install_smilies(&$module)
{
    $data = [
        [':-D',      'smilies/smil3dbd4d4e4c4f2.gif', 'Very Happy',           1],
        [':+1:',     'smilies/smil5cfb072f20c15.png', 'Thumbs Up',            1],
        [':-)',      'smilies/smil3dbd4d6422f04.gif', 'Smile',                1],
        [':-(',      'smilies/smil3dbd4d75edb5e.gif', 'Sad',                  1],
        [':-o',      'smilies/smil3dbd4d8676346.gif', 'Surprised',            1],
        [':-?',      'smilies/smil3dbd4d99c6eaa.gif', 'Confused',             1],
        ['8-)',      'smilies/smil3dbd4daabd491.gif', 'Cool',                 1],
        [':lol:',    'smilies/smil3dbd4dbc14f3f.gif', 'Laughing',             1],
        [':-x',      'smilies/smil3dbd4dcd7b9f4.gif', 'Mad',                  1],
        [':-P',      'smilies/smil3dbd4ddd6835f.gif', 'Razz',                 1],
        [':oops:',   'smilies/smil3dbd4df1944ee.gif', 'Embarrassed',          0],
        [':cry:',    'smilies/smil3dbd4e02c5440.gif', 'Crying (very sad)',    0],
        [':evil:',   'smilies/smil3dbd4e1748cc9.gif', 'Evil or Very Mad',     0],
        [':roll:',   'smilies/smil3dbd4e29bbcc7.gif', 'Rolling Eyes',         0],
        [';-)',      'smilies/smil3dbd4e398ff7b.gif', 'Wink',                 0],
        [':pint:',   'smilies/smil3dbd4e4c2e742.gif', 'Another pint of beer', 0],
        [':hammer:', 'smilies/smil3dbd4e5e7563a.gif', 'ToolTimes at work',    0],
        [':idea:',   'smilies/smil3dbd4e7853679.gif', 'I have an idea',       0],
    ];
    $types = [ParameterType::STRING, ParameterType::STRING, ParameterType::STRING, ParameterType::INTEGER];
    $db = \Xoops::getInstance()->db();
    foreach ($data as $sm) {
        list($smiley_code, $smiley_url, $smiley_emotion, $smiley_display) = $sm;
        $smiley = [
            'smiley_code' => $smiley_code,
            'smiley_url' => $smiley_url,
            'smiley_emotion' => $smiley_emotion,
            'smiley_display' => $smiley_display,
        ];
        $db->insertPrefix('smilies', $smiley, $types);
    }
    return true;
}
