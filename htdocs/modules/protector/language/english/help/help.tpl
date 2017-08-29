<h4>Description</h4>
<p>
    Protector is a module to defend your XOOPS CMS from various malicious attacks.
</p>
<h4>Install/uninstall</h4>
<p>
    First, define XOOPS_TRUST_PATH into mainfile.php if you've never done it yet.
    <br />
    Copy html/modules/protector in the archive into your XOOPS_ROOT_PATH/modules/
    Copy xoops_trust_path/modules/protector in the archive into your XOOPS_TRUST_PATH/modules/
    <br />
    Turn permission of XOOPS_TRUST_PATH/modules/protector/configs writable
</p>
<h4>= How to rescue =</h4>
<p>
    If you've been banned from Protector, just delete files under XOOPS_TRUST_PATH/modules/protector/configs/
</p>
<h4>Introduction for filter-plugins in this archive.</h4>
<p>- postcommon_post_deny_by_rbl.php
    <br />
    an anti-SPAM plugin.
    <br />
    All of Post from IP registered in RBL will be rejected.
    <br />
    This plugin can slow the performance of Post, especially chat modules.
</p>
<p>- postcommon_post_deny_by_httpbl.php
    <br />
    an anti-SPAM plugin.
    <br />
    All of Post from IP registered in http:BL will be rejected.
    <br />
    Before using it, get HTTPBL_KEY from http://www.projecthoneypot.org/ and set it into the filter file.
    <br />
    define( 'PROTECTOR_HTTPBL_KEY' , '............' ) ;
</p>
<p>- postcommon_post_need_multibyte.php
    <br />
    an anti-SPAM plugin.
    <br />
    Post without multi-byte characters will be rejected.
    <br />
    This plugin is only for sites of japanese, tchinese, schinese, and korean.
</p>
<p>- postcommon_post_htmlpurify4guest.php
    <br />
    All post data sent by guests will be purified by HTMLPurifier.
    <br />
    If you allow guests posting HTML, I strongly recommend you to enable it.
</p>
<p>-postcommon_register_insert_js_check.php
    <br />
    This plugin prevents your site from robot's user registering.
    <br />
    Required JavaScript working on the vistors browser.
</p>
<p>- bruteforce_overrun_message.php
    <br />
    Specify a message for visitors tried wrong passwords more than the specified times.
    <br />
    All plugins named *_message.php specifys the message for rejected accesses.
</p>
<p>- precommon_bwlimit_errorlog.php
    <br />
    When band width limitaion works unfortunately, this plugin logs it into Apache's error_log.
</p>
<p>All plugins named *_errorlog.php log some informations into Apaches error_log.</p>
<h4>Tutorial</h4>
<p>
    Tutorial coming soon.
</p>