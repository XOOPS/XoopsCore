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
 * Installer template file
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      Kris <kris@frxoops.org>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
 **/

defined('XOOPS_INSTALL') or die('XOOPS Installation wizard die');

$pageHasHelp = $_SESSION['pageHasHelp'];
$pageHasForm = $_SESSION['pageHasForm'];
$content = $_SESSION['content'];

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];

$keys = array_keys($wizard->pages);
$current = $wizard->pageIndex;
if ($current == 0) {

    $pages = array(
        array(
            'name' => $wizard->pages[$wizard->currentPage]['name'], 'index' => $wizard->pageIndex + 1,
            'current' => true, 'past' => false
        ), array(
            'name' => $wizard->pages[$keys[$current + 1]]['name'], 'index' => $wizard->pageIndex + 2,
            'current' => false, 'past' => false
        ), array(
            'name' => $wizard->pages[$keys[$current + 2]]['name'], 'index' => $wizard->pageIndex + 3,
            'current' => false, 'past' => false
        ), array(
            'name' => $wizard->pages[$keys[$current + 3]]['name'], 'index' => $wizard->pageIndex + 4,
            'current' => false, 'past' => false
        )
    );
} elseif ($current > 0 && $current < count($keys) - 1) {
    $pages = array(
        array(
            'name' => $wizard->pages[$keys[$current - 1]]['name'], 'index' => $wizard->pageIndex, 'current' => false,
            'past' => true
        ), array(
            'name' => $wizard->pages[$wizard->currentPage]['name'], 'index' => $wizard->pageIndex + 1,
            'current' => true, 'past' => false
        ), array(
            'name' => $wizard->pages[$keys[$current + 1]]['name'], 'index' => $wizard->pageIndex + 2,
            'current' => false, 'past' => false
        )/*, array(
            'name' => $wizard->pages[$keys[$current + 2]]['name'], 'index' => $wizard->pageIndex + 3,
            'current' => false, 'past' => false
        ), array(
                'name' => $wizard->pages[$keys[$current + 3]]['name'], 'index' => $wizard->pageIndex + 4,
                'current' => false, 'past' => false
        )  */
    );
} else {
    $pages = array();
    for ($i = count($keys) - 3; $i < count($keys); ++$i) {
        $pages[] = array(
            'name' => $wizard->pages[$keys[$i]]['name'], 'index' => $i,
            'current' => $i == $wizard->pageIndex ? true : false, 'past' => $i < $wizard->pageIndex ? true : false
        );
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo _LANGCODE; ?>" lang="<?php echo _LANGCODE; ?>">

<head>
    <title>
        <?php echo XOOPS_VERSION . ' : ' . XOOPS_INSTALL_WIZARD; ?>
        (<?php echo ($wizard->pageIndex + 1) . '/' . count($wizard->pages); ?>)
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo _INSTALL_CHARSET ?>"/>
    <link rel="shortcut icon" type="image/ico" href="../favicon.ico"/>
    <link charset="UTF-8" rel="stylesheet" type="text/css" media="all" href="css/style.css"/>
    <?php
    if (file_exists('locale/' . $wizard->language . '/style.css')) {
        echo '<link charset="UTF-8" rel="stylesheet" type="text/css" media="all" href="locale/' . $wizard->language . '/style.css" />';
    }
    ?>

    <script type="text/javascript" src="./js/prototype-1.6.0.3.js"></script>
    <script type="text/javascript" src="./js/xo-installer.js"></script>
</head>

<body>
<div id="xo-main-logo">
    <img src="img/logo.png" alt="XOOPS"/>
</div>
<div id="xo-container-brd">&nbsp;</div>
<div id="xo-container">
    <div id="xo-header" class="gradient_bar">
        <!--<div class="xo_title_c"><div class="xo_title gradient_bar"><?php echo XOOPS_INSTALL_WIZARD; ?></div></div>-->
        <ul>
            <?php foreach ($pages as $page): ?>
            <li<?php echo $page['current'] ? ' class="current"' : ''; ?><?php echo $page['past'] ? ' class="past"' : ''; ?>>
                <?php echo $page['name']; ?>
                <span><?php echo $page['index']; ?><?php if ($page['past']): ?><img src="./img/yes.png"
                                                                                    alt=""/><?php endif; ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <form id='<?php echo $wizard->pages[$wizard->currentPage]['name']; ?>' action='<?php echo $_SERVER['PHP_SELF']; ?>'
          method='post'>
        <div id="xo-page-title">
            <?php if (@$pageHasHelp) { echo "<img id=\"help_button\" src=\"./img/help.png\" alt=\"" . HELP_BUTTON_ALT . "\" title=\"" . HELP_BUTTON_ALT . "\" onclick=\"document.body.className = 'show-help';\" />"; }; ?>
            <span class="index"><?php echo $wizard->pageIndex + 1; ?></span>
            <span class="setup"><?php echo XOOPS_INSTALL_WIZARD; ?></span>
            <span class="title"><?php echo $wizard->pages[$wizard->currentPage]['title']; ?></span>
        </div>
        <div id="xo-page">
            <?php echo $content; ?>
        </div>
        <div id="buttons">
            <?php if ($wizard->pageIndex != 0) { ?>
            <button type="button" class="buttong" accesskey="p"
                    onclick="location.href='<?php echo $wizard->pageURI('-1'); ?>'">
                <?php echo BUTTON_PREVIOUS; ?>
            </button>
            <?php } ?>
            <?php if (@$pageHasForm) { ?>
            <button type="submit" class="gradient_bar button">
            <?php } else { ?>
            <button type="button" class="gradient_bar button" accesskey="n"
                    onclick="location.href='<?php echo $wizard->pageURI('+1'); ?>'">
            <?php } ?>
            <?php echo BUTTON_NEXT; ?>
        </button>
        </div>
    </form>
</div>


</body>
</html>