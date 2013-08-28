<?php
    defined('XOOPS_ROOT_PATH') or die();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title><?php echo _XOOPS_UPGRADE; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo _UPGRADE_CHARSET ?>" />
    <link rel="stylesheet" type="text/css" media="all" href="style.css" />
    <?php
        if (file_exists('language/' . $upgrade_language . '/style.css')) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="language/' . $upgrade_language . '/style.css" />';
        }
    ?>
</head>
<body>
<!--div id="xo-banner">
    <img src="img/logo.png" alt="XOOPS" />
</div-->
<div id="xo-content">
    <h1><?php echo _XOOPS_UPGRADE; ?></h1>

    <?php echo $content; ?>

</div>
</body>
</html>