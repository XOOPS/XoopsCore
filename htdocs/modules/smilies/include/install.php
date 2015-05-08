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
 * smilies module - install supplement for smilies module
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
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
    $data = array(
        array(':-D',      'smilies/smil3dbd4d4e4c4f2.gif', 'Very Happy',           1),
        array(':-)',      'smilies/smil3dbd4d6422f04.gif', 'Smile',                1),
        array(':-(',      'smilies/smil3dbd4d75edb5e.gif', 'Sad',                  1),
        array(':-o',      'smilies/smil3dbd4d8676346.gif', 'Surprised',            1),
        array(':-?',      'smilies/smil3dbd4d99c6eaa.gif', 'Confused',             1),
        array('8-)',      'smilies/smil3dbd4daabd491.gif', 'Cool',                 1),
        array(':lol:',    'smilies/smil3dbd4dbc14f3f.gif', 'Laughing',             1),
        array(':-x',      'smilies/smil3dbd4dcd7b9f4.gif', 'Mad',                  1),
        array(':-P',      'smilies/smil3dbd4ddd6835f.gif', 'Razz',                 1),
        array(':oops:',   'smilies/smil3dbd4df1944ee.gif', 'Embaressed',           0),
        array(':cry:',    'smilies/smil3dbd4e02c5440.gif', 'Crying (very sad)',    0),
        array(':evil:',   'smilies/smil3dbd4e1748cc9.gif', 'Evil or Very Mad',     0),
        array(':roll:',   'smilies/smil3dbd4e29bbcc7.gif', 'Rolling Eyes',         0),
        array(';-)',      'smilies/smil3dbd4e398ff7b.gif', 'Wink',                 0),
        array(':pint:',   'smilies/smil3dbd4e4c2e742.gif', 'Another pint of beer', 0),
        array(':hammer:', 'smilies/smil3dbd4e5e7563a.gif', 'ToolTimes at work',    0),
        array(':idea:',   'smilies/smil3dbd4e7853679.gif', 'I have an idea',       0),
    );
    $types = array(\PDO::PARAM_STR, \PDO::PARAM_STR, \PDO::PARAM_STR, \PDO::PARAM_INT);
    $db = \Xoops::getInstance()->db();
    foreach ($data as $sm) {
        list($smiley_code, $smiley_url, $smiley_emotion, $smiley_display) = $sm;
        $smiley = array(
            'smiley_code' => $smiley_code,
            'smiley_url' => $smiley_url,
            'smiley_emotion' => $smiley_emotion,
            'smiley_display' => $smiley_display,
        );
        $db->insertPrefix('smilies', $smiley, $types);
    }
    return true;
}
