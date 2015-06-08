<?php
// $Id$
// _LANGCODE: en
// _CHARSET : UTF-8
// Translator: XOOPS Translation Team

$installer_modified = $_SESSION['installer_modified'];
$content =
"<h3>Your site</h3>
<p>You can now access the <a href='../index.php'>home page of your site</a>.</p>
<h3>Support</h3>
<p>Visit <a href='http://xoops.org/' rel='external'>XOOPS Project</a></p>
<p><strong>ATTENTION :</strong> Your site currently contains the minimum functionality, if you want to add content: text pages, photo gallery, forum, links directory, ... You must first download from the <a href='http://www.xoops.org/modules/repository' rel='external' title='Choice and download modules'>library of your local support</a> and install these components, it is the same for <a href='http://www.xoops.org/modules/extgallery' rel='external' title='Select and Install new themes'>design (theme) Additional</a>.</p>
";

$content .=
"<h3>Security configuration</h3>
<p>The installer will try to configure your site for security considerations. Please double check to make sure:
<div class='confirmMsg'>
The <em>mainfile.php</em> is readonly.<br />
Remove the folder <em>{$installer_modified}</em> (or <em>install</em> if it was not renamed automatically by the installer)  from your server.
</div>
</p>
";

$_SESSION['content'] = $content;
