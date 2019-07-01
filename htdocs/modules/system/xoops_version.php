<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY, without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * System menu
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     system
 * @author      Kazumi Ono (AKA onokazu)
 * @version     $Id$
 */

/*
 General settings
 */
$modversion['name'] = SystemLocale::MODULE_NAME;
$modversion['description'] = SystemLocale::MODULE_DESCRIPTION;
$modversion['version'] = 2.10;
$modversion['author'] = 'Andricq Nicolas,Cointin Maxime,Mage Gregory';
$modversion['nickname'] = 'MusS,Kraven30,Mage';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'http://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 1;
$modversion['help'] = 'system.html';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'system';

/*
 Settings for configs
*/
$modversion['release_date'] = '2011/12/20';
$modversion['module_website_url'] = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA';
$modversion['min_php'] = '5.3.7';
$modversion['min_xoops'] = '2.6.0';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin.php';
$modversion['adminmenu'] = 'menu.php';

$modversion['onUpdate'] = 'include/update.php';
$modversion['onInstall'] = 'include/install.php';

// JQuery
$modversion['jquery'] = 1;

// table definitions
$modversion['schema'] = 'sql/schema.yml';

// Tables created by sql file or schema (without prefix!)
$modversion['tables'] = [
    'system_blockmodule',
    'system_config',
    'system_configoption',
    'system_group',
    'system_permission',
    'system_usergroup',
    'system_module',
    'system_block',
    'system_online',
    'system_privatemessage',
    'system_session',
    'system_tplset',
    'system_tplfile',
    'system_tplsource',
    'system_user',
];

// Admin Templates
// Blocks
$modversion['blocks'][] = [
    'file' => 'user.php',
    'name' => SystemLocale::BLOCK_USER_MENU,
    'description' => SystemLocale::BLOCK_USER_MENU_DESC,
    'show_func' => 'b_system_user_show',
    'template' => 'system_block_user.tpl',
];

$modversion['blocks'][] = [
    'file' => 'login.php',
    'name' => SystemLocale::BLOCK_LOGIN,
    'description' => SystemLocale::BLOCK_LOGIN_DESC,
    'show_func' => 'b_system_login_show',
    'template' => 'system_block_login.tpl',
];

$modversion['blocks'][] = [
    'file' => 'waiting.php',
    'name' => SystemLocale::BLOCK_WAITING_CONTENTS,
    'description' => SystemLocale::BLOCK_WAITING_CONTENTS_DESC,
    'show_func' => 'b_system_waiting_show',
    'template' => 'system_block_waiting.tpl',
];

$modversion['blocks'][] = [
    'file' => 'main.php',
    'name' => SystemLocale::BLOCK_MAIN_MENU,
    'description' => SystemLocale::BLOCK_MAIN_MENU_DESC,
    'show_func' => 'b_system_main_show',
    'edit_func' => 'b_system_main_edit',
    'template' => 'system_block_mainmenu.tpl',
];

$modversion['blocks'][] = [
    'file' => 'info.php',
    'name' => SystemLocale::BLOCK_SITE_INFORMATION,
    'description' => SystemLocale::BLOCK_SITE_INFORMATION,
    'show_func' => 'b_system_info_show',
    'edit_func' => 'b_system_info_edit',
    'options' => '320|190|s_poweredby.gif|1',
    'template' => 'system_block_siteinfo.tpl',
];

$modversion['blocks'][] = [
    'file' => 'online.php',
    'name' => SystemLocale::BLOCK_WHO_IS_ONLINE,
    'description' => SystemLocale::BLOCK_WHO_IS_ONLINE_DESC,
    'show_func' => 'b_system_online_show',
    'template' => 'system_block_online.tpl',
];

$modversion['blocks'][] = [
    'file' => 'topposters.php',
    'name' => SystemLocale::BLOCK_TOP_POSTERS,
    'description' => SystemLocale::BLOCK_TOP_POSTERS_DESC,
    'show_func' => 'b_system_topposters_show',
    'edit_func' => 'b_system_topposters_edit',
    'options' => '10|1',
    'template' => 'system_block_topusers.tpl',
];

$modversion['blocks'][] = [
    'file' => 'newmembers.php',
    'name' => SystemLocale::BLOCK_NEW_MEMBERS,
    'description' => SystemLocale::BLOCK_NEW_MEMBERS_DESC,
    'show_func' => 'b_system_newmembers_show',
    'options' => '10|1',
    'edit_func' => 'b_system_newmembers_edit',
    'template' => 'system_block_newusers.tpl',
];

$modversion['blocks'][] = [
    'file' => 'themes.php',
    'name' => SystemLocale::BLOCK_THEMES,
    'description' => SystemLocale::BLOCK_THEMES_DESC,
    'show_func' => 'b_system_themes_show',
    'options' => '0|80',
    'edit_func' => 'b_system_themes_edit',
    'template' => 'system_block_themes.tpl',
];

// Menu
$modversion['hasMain'] = 0;

// Config categories
$modversion['configcat']['general'] = [
    'name' => SystemLocale::GENERAL_SETTINGS,
    'description' => '',
];

$modversion['configcat']['user'] = [
    'name' => XoopsLocale::USER,
    'description' => '',
];

$modversion['configcat']['meta'] = [
    'name' => SystemLocale::META_TAGS_AND_FOOTER,
    'description' => '',
];

$modversion['configcat']['mail'] = [
    'name' => XoopsLocale::EMAIL,
    'description' => '',
];

$modversion['configcat']['censor'] = [
    'name' => SystemLocale::WORD_CENSORING,
    'description' => '',
];

$modversion['configcat']['authentication'] = [
    'name' => SystemLocale::AUTHENTICATION,
    'description' => '',
];

// Site preferences
// Category general
$modversion['config'][] = [
    'name' => 'sitename',
    'title' => 'CONF_SITE_NAME',
    'description' => '',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => SystemLocale::CONF_SITE_NAME_DEFAULT,
];

$modversion['config'][] = [
    'name' => 'slogan',
    'title' => 'CONF_SLOGAN',
    'description' => '',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => SystemLocale::CONF_SLOGAN_DEFAULT,
];

$modversion['config'][] = [
    'name' => 'adminmail',
    'title' => 'CONF_ADMIN_EMAIL',
    'description' => '',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '',
];

$modversion['config'][] = [
    'name' => 'locale',
    'title' => 'CONF_LOCALE',
    'description' => '',
    'category' => 'general',
    'formtype' => 'locale',
    'valuetype' => 'other',
    'default' => 'en_US',
];

$modversion['config'][] = [
    'name' => 'startpage',
    'title' => 'CONF_START_PAGE',
    'description' => '',
    'category' => 'general',
    'formtype' => 'startpage',
    'valuetype' => 'other',
    'default' => '--',
];

$modversion['config'][] = [
    'name' => 'server_TZ',
    'title' => 'CONF_SERVER_TIMEZONE',
    'description' => '',
    'category' => 'general',
    'formtype' => 'timezone',
    'valuetype' => 'other',
    'default' => 'UTC',
];

$modversion['config'][] = [
    'name' => 'default_TZ',
    'title' => 'CONF_DEFAULT_TIMEZONE',
    'description' => '',
    'category' => 'general',
    'formtype' => 'timezone',
    'valuetype' => 'other',
    'default' => 'UTC',
];

$modversion['config'][] = [
    'name' => 'theme_set',
    'title' => 'CONF_THEME_SET',
    'description' => '',
    'category' => 'general',
    'formtype' => 'theme',
    'valuetype' => 'other',
    'default' => 'default',
];

$modversion['config'][] = [
    'name' => 'cpanel',
    'title' => 'CONF_CONTROL_PANEL',
    'description' => 'CONF_CONTROL_PANEL_DESC',
    'category' => 'general',
    'formtype' => 'cpanel',
    'valuetype' => 'other',
    'default' => 'default',
];

$modversion['config'][] = [
    'name' => 'redirect_message_ajax',
    'title' => 'CONF_REDIRECT',
    'description' => 'CONF_REDIRECT_DESC',
    'category' => 'general',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'theme_set_allowed',
    'title' => 'CONF_THEME_SET_ALLOWED',
    'description' => 'CONF_THEME_SET_ALLOWED_DESC',
    'category' => 'general',
    'formtype' => 'theme_multi',
    'valuetype' => 'array',
    'default' => ['default' => 'default'],
];

$modversion['config'][] = [
    'name' => 'theme_fromfile',
    'title' => 'CONF_THEME_FILE',
    'description' => 'CONF_THEME_FILE_DESC',
    'category' => 'general',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'template_set',
    'title' => 'CONF_TEMPLATE_SET',
    'description' => '',
    'category' => 'general',
    'formtype' => 'tplset',
    'valuetype' => 'other',
    'default' => 'default',
];

$modversion['config'][] = [
    'name' => 'anonymous',
    'title' => 'CONF_ANONYMOUS',
    'description' => '',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => XoopsLocale::ANONYMOUS,
];

$modversion['config'][] = [
    'name' => 'gzip_compression',
    'title' => 'CONF_GZIP_COMPRESSION',
    'description' => '',
    'category' => 'general',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'usercookie',
    'title' => 'CONF_USER_COOKIE',
    'description' => 'CONF_USER_COOKIE_DESC',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'xoops_user_' . dechex(time()),
];

$modversion['config'][] = [
    'name' => 'session_name',
    'title' => 'CONF_SESSION_NAME',
    'description' => 'CONF_SESSION_NAME_DESC',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'xoops_session_' . dechex(time()),
];

$modversion['config'][] = [
    'name' => 'session_expire',
    'title' => 'CONF_SESSION_EXPIRE',
    'description' => 'CONF_SESSION_EXPIRE_DESC',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 15,
];

$modversion['config'][] = [
    'name' => 'closesite',
    'title' => 'CONF_CLOSE_SITE',
    'description' => 'CONF_CLOSE_SITE_DESC',
    'category' => 'general',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'closesite_okgrp',
    'title' => 'CONF_CLOSE_SITE_GROUP',
    'description' => 'CONF_CLOSE_SITE_GROUP_DESC',
    'category' => 'general',
    'formtype' => 'group_multi',
    'valuetype' => 'array',
    'default' => ['1'],
];

$modversion['config'][] = [
    'name' => 'closesite_text',
    'title' => 'CONF_CLOSE_SITE',
    'description' => 'CONF_CLOSE_SITE_TEXT_DESC',
    'category' => 'general',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => SystemLocale::CONF_CLOSE_SITE_DEFAULT,
];

$modversion['config'][] = [
    'name' => 'use_ssl',
    'title' => 'CONF_USE_SSL',
    'description' => 'CONF_USE_SSL_DESC',
    'category' => 'general',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'sslpost_name',
    'title' => 'CONF_SSL_POST_NAME',
    'description' => 'CONF_SSL_POST_NAME_DESC',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'xoops_ssl',
];

$modversion['config'][] = [
    'name' => 'sslloginlink',
    'title' => 'CONF_SSL_LINK',
    'description' => '',
    'category' => 'general',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'https://',
];

$modversion['config'][] = [
    'name' => 'enable_badips',
    'title' => 'CONF_ENABLE_BAD_IPS',
    'description' => 'CONF_ENABLE_BAD_IPS_DESC',
    'category' => 'general',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'bad_ips',
    'title' => 'CONF_BAD_IPS',
    'description' => 'CONF_BAD_IPS_DESC',
    'category' => 'general',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => ['127.0.0.1'],
];

$modversion['config'][] = [
    'name' => 'module_cache',
    'title' => 'CONF_MODULE_CACHE',
    'description' => 'CONF_MODULE_CACHE_DESC',
    'category' => 'general',
    'formtype' => 'module_cache',
    'valuetype' => 'array',
    'default' => '',
];

// Category user

$modversion['config'][] = [
    'name' => 'allow_register',
    'title' => 'CONF_ALLOW_REGISTRATION',
    'description' => 'CONF_ALLOW_REGISTRATION_DESC',
    'category' => 'user',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'minpass',
    'title' => 'CONF_MIN_PASS',
    'description' => '',
    'category' => 'user',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 8,
];

$modversion['config'][] = [
    'name' => 'minuname',
    'title' => 'CONF_MIN_USERNAME',
    'description' => '',
    'category' => 'user',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 3,
];

$modversion['config'][] = [
    'name' => 'maxuname',
    'title' => 'CONF_MAX_USERNAME',
    'description' => '',
    'category' => 'user',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 10,
];

$modversion['config'][] = [
    'name' => 'allow_chgmail',
    'title' => 'CONF_ALLOW_CHANGE_EMAIL',
    'description' => '',
    'category' => 'user',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'welcome_type',
    'title' => 'CONF_WELCOME_TYPE',
    'description' => 'CONF_WELCOME_TYPE_DESC',
    'category' => 'user',
    'formtype' => 'select',
    'valuetype' => 'int',
    'options' => [
        'CONF_WELCOME_TYPE_NONE' => 0,
        'CONF_WELCOME_TYPE_EMAIL' => 1,
        'CONF_WELCOME_TYPE_PM' => 2,
        'CONF_WELCOME_TYPE_BOTH' => 3,
    ],
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'new_user_notify',
    'title' => 'CONF_NEW_USER_NOTIFY',
    'description' => '',
    'category' => 'user',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'new_user_notify_group',
    'title' => 'CONF_NOTIFY_TO',
    'description' => '',
    'category' => 'user',
    'formtype' => 'group',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'activation_type',
    'title' => 'CONF_ACTIVATION_TYPE',
    'description' => '',
    'category' => 'user',
    'formtype' => 'select',
    'valuetype' => 'int',
    'options' => [
        'CONF_USER_ACTIVATION' => 0,
        'CONF_AUTO_ACTIVATION' => 1,
        'CONF_ADMIN_ACTIVATION' => 2,
    ],
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'activation_group',
    'title' => 'CONF_ACTIVATION_GROUP',
    'description' => 'CONF_ACTIVATION_GROUP_DESC',
    'category' => 'user',
    'formtype' => 'group',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'self_delete',
    'title' => 'CONF_SELF_DELETE',
    'description' => '',
    'category' => 'user',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'bad_unames',
    'title' => 'CONF_BAD_USERNAMES',
    'description' => '',
    'category' => 'user',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => ['webmaster', '^xoops', '^admin'],
];

$modversion['config'][] = [
    'name' => 'bad_emails',
    'title' => 'CONF_BAD_EMAILS',
    'description' => 'CONF_BAD_EMAILS_DESC',
    'category' => 'user',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => ['xoops.org$'],
];

$modversion['config'][] = [
    'name' => 'reg_dispdsclmr',
    'title' => 'CONF_DSPDSCLMR',
    'description' => 'CONF_DSPDSCLMR_DESC',
    'category' => 'user',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'reg_disclaimer',
    'title' => 'CONF_REGDSCLMR',
    'description' => 'CONF_REGDSCLMR_DESC',
    'category' => 'user',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => SystemLocale::CONF_DISCLAIMER_DEFAULT,
];

// Category meta

$modversion['config'][] = [
    'name' => 'meta_keywords',
    'title' => 'CONF_METAKEY',
    'description' => 'CONF_METAKEY_DESC',
    'category' => 'meta',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => SystemLocale::CONF_METAKEY_DEFAULT,
];

$modversion['config'][] = [
    'name' => 'meta_description',
    'title' => 'CONF_METADESC',
    'description' => 'CONF_METADESC_DESC',
    'category' => 'meta',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => SystemLocale::CONF_METADESC_DEFAULT,
];

$modversion['config'][] = [
    'name' => 'meta_robots',
    'title' => 'CONF_METAROBOTS',
    'description' => 'CONF_METAROBOTS_DESC',
    'category' => 'meta',
    'formtype' => 'select',
    'valuetype' => 'text',
    'options' => [
        'CONF_INDEXFOLLOW' => 'index,follow',
        'CONF_NOINDEXFOLLOW' => 'noindex,follow',
        'CONF_INDEXNOFOLLOW' => 'index,nofollow',
        'CONF_NOINDEXNOFOLLOW' => 'noindex,nofollow',
    ],
    'default' => 'index,follow',
];

$modversion['config'][] = [
    'name' => 'meta_rating',
    'title' => 'CONF_METARATING',
    'description' => 'CONF_METARATING_DESC',
    'category' => 'meta',
    'formtype' => 'select',
    'valuetype' => 'text',
    'options' => [
        'CONF_METAOGEN' => 'general',
        'CONF_METAO14YRS' => '14 years',
        'CONF_METAOREST' => 'restricted',
        'CONF_METAOMAT' => 'mature',
    ],
    'default' => 'general',
];

$modversion['config'][] = [
    'name' => 'meta_author',
    'title' => 'CONF_METAAUTHOR',
    'description' => 'CONF_METAAUTHOR_DESC',
    'category' => 'meta',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'XOOPS',
];

$modversion['config'][] = [
    'name' => 'meta_copyright',
    'title' => 'CONF_METACOPYR',
    'description' => 'CONF_METACOPYR_DESC',
    'category' => 'meta',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => sprintf(SystemLocale::CONF_METACOPYR_DEFAULT, date('Y', time())),
];

$modversion['config'][] = [
    'name' => 'footer',
    'title' => 'CONF_FOOTER',
    'description' => 'CONF_FOOTER_DESC',
    'category' => 'meta',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => sprintf(SystemLocale::CONF_FOOTER_DEFAULT, date('Y', time())),
];

// Category mail

$modversion['config'][] = [
    'name' => 'from',
    'title' => 'CONF_MAILFROM',
    'description' => '',
    'category' => 'mail',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '',
];

$modversion['config'][] = [
    'name' => 'fromname',
    'title' => 'CONF_MAILFROMNAME',
    'description' => '',
    'category' => 'mail',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '',
];

$modversion['config'][] = [
    'name' => 'fromuid',
    'title' => 'CONF_MAILFROMUID',
    'description' => 'CONF_MAILFROMUID_DESC',
    'category' => 'mail',
    'formtype' => 'user',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'mailmethod',
    'title' => 'CONF_MAILERMETHOD',
    'description' => 'CONF_MAILERMETHOD_DESC',
    'category' => 'mail',
    'formtype' => 'select',
    'valuetype' => 'text',
    'options' => [
        'PHP mail()' => 'mail',
        'sendmail' => 'sendmail',
        'SMTP' => 'smtp',
        'SMTPAuth' => 'smtpauth',
    ],
    'default' => 'mail',
];

$modversion['config'][] = [
    'name' => 'sendmailpath',
    'title' => 'CONF_SENDMAILPATH',
    'description' => 'CONF_SENDMAILPATH_DESC',
    'category' => 'mail',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '/usr/sbin/sendmail',
];

$modversion['config'][] = [
    'name' => 'smtphost',
    'title' => 'CONF_SMTPHOST',
    'description' => 'CONF_SMTPHOST_DESC',
    'category' => 'mail',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => '',
];

$modversion['config'][] = [
    'name' => 'smtpuser',
    'title' => 'CONF_SMTPUSER',
    'description' => 'CONF_SMTPUSER_DESC',
    'category' => 'mail',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '',
];

$modversion['config'][] = [
    'name' => 'smtppass',
    'title' => 'CONF_SMTPPASS',
    'description' => 'CONF_SMTPPASS_DESC',
    'category' => 'mail',
    'formtype' => 'password',
    'valuetype' => 'text',
    'default' => '',
];

// Category censor

$modversion['config'][] = [
    'name' => 'censor_enable',
    'title' => 'CONF_DOCENSOR',
    'description' => 'CONF_DOCENSOR_DESC',
    'category' => 'censor',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'censor_words',
    'title' => 'CONF_CENSORWRD',
    'description' => 'CONF_CENSORWRD_DESC',
    'category' => 'censor',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => ['fuck', 'shit'],
];

$modversion['config'][] = [
    'name' => 'censor_replace',
    'title' => 'CONF_CENSORRPLC',
    'description' => 'CONF_CENSORRPLC_DESC',
    'category' => 'censor',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '#OOPS#',
];

// Category authentication

$modversion['config'][] = [
    'name' => 'auth_method',
    'title' => 'CONF_AUTHMETHOD',
    'description' => 'CONF_AUTHMETHOD_DESC',
    'category' => 'authentication',
    'formtype' => 'select',
    'valuetype' => 'text',
    'options' => [
        'CONF_AUTH_CONFOPTION_XOOPS' => 'xoops',
        'CONF_AUTH_CONFOPTION_LDAP' => 'ldap',
        'CONF_AUTH_CONFOPTION_AD' => 'ads',
    ],
    'default' => 'xoops',
];

$modversion['config'][] = [
    'name' => 'ldap_port',
    'title' => 'CONF_LDAP_PORT',
    'description' => '',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 389,
];

$modversion['config'][] = [
    'name' => 'ldap_server',
    'title' => 'CONF_LDAP_SERVER',
    'description' => 'CONF_LDAP_SERVER_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'your directory server',
];

$modversion['config'][] = [
    'name' => 'ldap_base_dn',
    'title' => 'CONF_LDAP_BASE_DN',
    'description' => 'CONF_LDAP_BASE_DN_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'dc=xoops,dc=org',
];

$modversion['config'][] = [
    'name' => 'ldap_manager_dn',
    'title' => 'CONF_LDAP_MANAGER_DN',
    'description' => 'CONF_LDAP_MANAGER_DN_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'manager_dn',
];

$modversion['config'][] = [
    'name' => 'ldap_manager_pass',
    'title' => 'CONF_LDAP_MANAGER_PASS',
    'description' => 'CONF_LDAP_MANAGER_PASS_DESC',
    'category' => 'authentication',
    'formtype' => 'password',
    'valuetype' => 'text',
    'default' => 'manager_pass',
];

$modversion['config'][] = [
    'name' => 'ldap_version',
    'title' => 'CONF_LDAP_VERSION',
    'description' => 'CONF_LDAP_VERSION_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '3',
];

$modversion['config'][] = [
    'name' => 'ldap_users_bypass',
    'title' => 'CONF_LDAP_USERS_BYPASS',
    'description' => 'CONF_LDAP_USERS_BYPASS_DESC',
    'category' => 'authentication',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => ['admin'],
];

$modversion['config'][] = [
    'name' => 'ldap_loginname_asdn',
    'title' => 'CONF_LDAP_LOGINNAME_ASDN',
    'description' => 'CONF_LDAP_LOGINNAME_ASDN_DESC',
    'category' => 'authentication',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'ldap_loginldap_attr',
    'title' => 'CONF_LDAP_LOGINLDAP_ATTR',
    'description' => 'CONF_LDAP_LOGINLDAP_ATTR_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'uid',
];

$modversion['config'][] = [
    'name' => 'ldap_filter_person',
    'title' => 'CONF_LDAP_FILTER_PERSON',
    'description' => 'CONF_LDAP_FILTER_PERSON_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '',
];

$modversion['config'][] = [
    'name' => 'ldap_domain_name',
    'title' => 'CONF_LDAP_DOMAIN_NAME',
    'description' => 'CONF_LDAP_DOMAIN_NAME_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'mydomain',
];

$modversion['config'][] = [
    'name' => 'ldap_provisionning',
    'title' => 'CONF_LDAP_PROVIS',
    'description' => 'CONF_LDAP_PROVIS_DESC',
    'category' => 'authentication',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

$modversion['config'][] = [
    'name' => 'ldap_provisionning_group',
    'title' => 'CONF_LDAP_PROVIS_GROUP',
    'description' => 'CONF_LDAP_PROVIS_GROUP_DESC',
    'category' => 'authentication',
    'formtype' => 'group_multi',
    'valuetype' => 'array',
    'default' => '2',
];

$modversion['config'][] = [
    'name' => 'ldap_mail_attr',
    'title' => 'CONF_LDAP_MAIL_ATTR',
    'description' => 'CONF_LDAP_MAIL_ATTR_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'mail',
];

$modversion['config'][] = [
    'name' => 'ldap_givenname_attr',
    'title' => 'CONF_LDAP_GIVENNAME_ATTR',
    'description' => 'CONF_LDAP_GIVENNAME_ATTR_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'givenname',
];

$modversion['config'][] = [
    'name' => 'ldap_surname_attr',
    'title' => 'CONF_LDAP_SURNAME_ATTR',
    'description' => 'CONF_LDAP_SURNAME_ATTR_DESC',
    'category' => 'authentication',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => 'sn',
];

$modversion['config'][] = [
    'name' => 'ldap_field_mapping',
    'title' => 'CONF_LDAP_FIELD_MAPPING_ATTR',
    'description' => 'CONF_LDAP_FIELD_MAPPING_DESC',
    'category' => 'authentication',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => 'email=mail|name=displayname',
];

$modversion['config'][] = [
    'name' => 'ldap_provisionning_upd',
    'title' => 'CONF_LDAP_PROVIS_UPD',
    'description' => 'CONF_LDAP_PROVIS_UPD_DESC',
    'category' => 'authentication',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'ldap_use_TLS',
    'title' => 'CONF_LDAP_USETLS',
    'description' => 'CONF_LDAP_USETLS_DESC',
    'category' => 'authentication',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0,
];

// no category?

$modversion['config'][] = [
    'name' => 'usetips',
    'title' => 'CONF_HELP_ONLINE',
    'description' => 'CONF_HELP_ONLINE_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$icons = XoopsLists::getDirListAsArray(\XoopsBaseConfig::get('root-path') . '/modules/system/images/icons');
$modversion['config'][] = [
    'name' => 'typeicons',
    'title' => 'CONF_ICONS',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'text',
    'default' => 'default',
    'options' => $icons,
];

$breadcrumb = XoopsLists::getDirListAsArray(\XoopsBaseConfig::get('root-path') . '/modules/system/images/breadcrumb');
$modversion['config'][] = [
    'name' => 'typebreadcrumb',
    'title' => 'CONF_BREADCRUMB',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'text',
    'default' => 'default',
    'options' => $breadcrumb,
];

$jquery_theme = XoopsLists::getDirListAsArray(\XoopsBaseConfig::get('root-path') . '/media/jquery/ui/themes');
$modversion['config'][] = [
    'name' => 'jquery_theme',
    'title' => 'CONF_JQUERY_THEME',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'text',
    'default' => 'base',
    'options' => $jquery_theme,
];

$modversion['config'][] = [
    'name' => 'active_blocksadmin',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_extensions',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_filemanager',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_groups',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_modulesadmin',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_preferences',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_services',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_tplsets',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'active_users',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'groups_pager',
    'title' => 'CONF_GROUPS_PER_PAGE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 15,
];

$modversion['config'][] = [
    'name' => 'users_pager',
    'title' => 'CONF_USERS_PER_PAGE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 20,
];

$modversion['config'][] = [
    'name' => 'blocks_editor',
    'title' => 'CONF_BLOCKS_EDITOR',
    'description' => '',
    'formtype' => 'select_editor',
    'valuetype' => 'text',
    'default' => 'dhtmltextarea',
];

$modversion['config'][] = [
    'name' => 'general_editor',
    'title' => 'CONF_GENERAL_EDITOR',
    'description' => '',
    'formtype' => 'select_editor',
    'valuetype' => 'text',
    'default' => 'dhtmltextarea',
];

$modversion['config'][] = [
    'name' => 'redirect',
    'title' => '',
    'description' => '',
    'formtype' => 'hidden',
    'valuetype' => 'textbox',
    'default' => 'admin.php?fct=preferences',
];
