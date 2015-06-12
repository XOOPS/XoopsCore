<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Yaml;

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 */

function xoops_module_install_userrank($module)
{
    $xoops = Xoops::getInstance();
    $xoops->header();
$lang_rank_titles = array(
    'dummy',
    _MI_RANK_TITLE_1,
    _MI_RANK_TITLE_2,
    _MI_RANK_TITLE_3,
    _MI_RANK_TITLE_4,
    _MI_RANK_TITLE_5,
    _MI_RANK_TITLE_6,
    _MI_RANK_TITLE_7,
);
    $filedata = <<<EOT
-
  rank_id: 1
  rank_title: "{$lang_rank_titles[1]}"
  rank_min: 0
  rank_max: 20
  rank_special: 0
  rank_image: "ranks/rank3e632f95e81ca.gif"
-
  rank_id: 2
  rank_title: "{$lang_rank_titles[2]}"
  rank_min: 21
  rank_max: 40
  rank_special: 0
  rank_image: "ranks/rank3dbf8e94a6f72.gif"
-
  rank_id: 3
  rank_title: "{$lang_rank_titles[3]}"
  rank_min: 41
  rank_max: 70
  rank_special: 0
  rank_image: "ranks/rank3dbf8e9e7d88d.gif"
-
  rank_id: 4
  rank_title: "{$lang_rank_titles[4]}"
  rank_min: 71
  rank_max: 150
  rank_special: 0
  rank_image: "ranks/rank3dbf8ea81e642.gif"
-
  rank_id: 5
  rank_title: "{$lang_rank_titles[5]}"
  rank_min: 151
  rank_max: 10000
  rank_special: 0
  rank_image: "ranks/rank3dbf8eb1a72e7.gif"
-
  rank_id: 6
  rank_title: "{$lang_rank_titles[6]}"
  rank_min: 0
  rank_max: 0
  rank_special: 1
  rank_image: "ranks/rank3dbf8edf15093.gif"
-
  rank_id: 7
  rank_title: "{$lang_rank_titles[7]}"
  rank_min: 0
  rank_max: 0
  rank_special: 1
  rank_image: "ranks/rank3dbf8ee8681cd.gif"
EOT;

    $tablerows = Yaml::load($filedata);

    $dbm = $xoops->db();
    $count = $dbm->fetchColumn('SELECT COUNT(*) FROM ' . $dbm->prefix("ranks"));
    if ($count<1) {
        $dbm->beginTransaction();
        foreach ($tablerows as $row) {
            $dbm->insertPrefix('ranks', $row);
        }
        $dbm->commit();
    }
    return true;
}
