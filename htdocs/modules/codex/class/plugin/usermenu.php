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
 * @copyright        2000-2020 XOOPS Project (https://xoops.org)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class CodexUsermenuPlugin implements UsermenuPluginInterface
{
    /**
     * @return array
     */
    public function usermenu()
    {
        $helper = \Xoops::getModuleHelper('codex');
        $subMenu = [];
        // Prevent wasting resources
        if ($helper->isCurrentModule()) {
            $files = \Xoops\Core\Lists\File::getList($helper->path('/'));
            $i = 0;
            foreach ($files as $file) {
                if (!in_array($file, ['xoops_version.php', 'index.php'])) {
                    $fileName = ucfirst(str_replace('.php', '', $file));
                    $subMenu[] = [
                        'name' => $fileName,
                        'link' => $file,
                    ];
                    ++$i;
                }
            }
        }

        $ret[] = [
            'name' => $helper->getModule()->getVar('name'),
            'link' => $helper->url('index.php'),
            'subMenu' => $subMenu,
        ];

        return $ret;
    }
}
