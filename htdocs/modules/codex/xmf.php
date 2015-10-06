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
 * @copyright       2011-2015 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 * @author          trabis <lusopoemas@gmail.com>
 */

use Xmf\Debug;
use Xmf\Highlighter;
use Xmf\Metagen;
use Xmf\Request;
use Xmf\Module\Permission;
use Xmf\Module\Session;

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

// work with session data in our module context
echo '<h2>Session demo</h2><h4>Toggle a session variable</h4>';
$sessionHelper = new Session();
if ($sessionHelper) {
    $var = $sessionHelper->get('fred');
    if ($var) {
        echo sprintf('Clearing session variable value of "%s"', $var) . '<br />';
        $sessionHelper->destroy();
    } else {
        $var = date('Y-m-d H:i:s');
        echo sprintf('Session variable not set. Setting as: %s', $var) . '<br />';
        $sessionHelper->set('fred', $var);
    }
}

echo '<h2>Permission demo</h2>';
$permissionHelper = new Permission();
if ($permissionHelper) {
    // this is the name and item we are going to work with
    $permissionName='fred';
    $permissionItemId=1;

    // if this is a post operation get the input and save it
    if ('POST'==Request::getMethod()) {
        echo $xoops->alert('success', 'Permission updated');
        // save the data
        $name=$permissionHelper->defaultFieldName($permissionName, $permissionItemId);
        $groups=Request::getVar($name, array(), $hash = 'POST');
        $permissionHelper->savePermissionForItem($permissionName, $permissionItemId, $groups);
    }

    // build a form for our permission
    $form = new Xoops\Form\ThemeForm(
        "Permission Form (for the permission named '$permissionName')",
        'form',
        '',
        'POST'
    );
    $groupElement = $permissionHelper->getGroupSelectFormForItem(
        $permissionName,
        $permissionItemId,
        "Groups with '$permissionName' permission",
        null,
        true
    );
    $form->addElement($groupElement);
    $form->addElement(new Xoops\Form\Button('', 'submit', 'Save', 'submit'));

    echo $form->render();

    // check it the current user has the permission
    if ($permissionHelper->checkPermission($permissionName, $permissionItemId)) {
        echo "<p>You have the <strong>'$permissionName'</strong> permission for the 'codex' module.</p>";
    } else {
        echo "<p>You <em>DO NOT</em> have the <strong>'$permissionName'</strong> " .
            "permission for the 'codex' module.</p>";
    }
}

echo '<h2>Metagen</h2>';

//define a title and article to work with
$keywords='';
$title="xmf - the XOOPS Module Framework";
$article =<<<EOT
xmf - XOOPS Module Framework

XMF is Copyright © 2011-2015 The XOOPS Project

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

Some portions of this work are licensed under the GNU Lesser
General Public License Version 2.1 as published by the Free Software
Foundation. Such portions are clearly identified in the source files.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License,
and the GNU Lesser General Public License along with this program.
If not, see http://www.gnu.org/licenses/.

You may contact the copyright holder through XOOPS Project:
http://xoops.org
EOT;

echo '<h4>Extracted Description</h4>';
// get the intro of the article to use as the description
$description = Metagen::generateDescription($article, 50);
echo '<p>' . $description . '</p>';

echo '<h4>SEO Slug</h4>';
// turn title into a slug
echo '<p>' . Metagen::generateSeoTitle($title) . '</p>';

// highlight keywords in article
echo '<h4>Article with Top 25 Keywords Highlighted</h4>';
// get important words from title
$title_keywords = Metagen::generateKeywords($title, 25, 3);
//Debug::dump($title_keywords);
// get top 25 keywords, but always keep keywords from title
$keywords = Metagen::generateKeywords($article, 25, 4, $title_keywords);
Debug::dump($keywords, true);
echo Highlighter::apply($keywords, $article);

// add to the page
Metagen::assignTitle($title);
Metagen::assignKeywords($keywords);
Metagen::assignDescription($description);

// dump our source
echo '<br /><h2>Source code</h2>';
Xoops_Utils::dumpFile(__FILE__);
$xoops->footer();
