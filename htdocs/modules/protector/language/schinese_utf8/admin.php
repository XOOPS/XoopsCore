<?php

// mymenu
define('_MD_A_MYMENU_MYTPLSADMIN', '');
define('_MD_A_MYMENU_MYBLOCKSADMIN', '权限设置');
define('_MD_A_MYMENU_MYPREFERENCES', '基本参数设置');

// index.php
define('_AM_TH_DATETIME', '时间');
define('_AM_TH_USER', '用户');
define('_AM_TH_IP', 'IP');
define('_AM_TH_AGENT', '浏览器');
define('_AM_TH_TYPE', '类型');
define('_AM_TH_DESCRIPTION', '描述');

define('_AM_TH_BADIPS', '恶意IP地址<br /><br /><span style="font-weight:normal;">每行一个IP地址<br />如果此项为空，则所有的IP地址都可以访问。</span>');

define('_AM_TH_GROUP1IPS', '允许登录管理员群组的IP地址（Group=1）<br /><br /><span style="font-weight:normal;">每行一个IP地址。<br />192.168. means 192.168.*<br />如果此项为空，则所有的IP地址都可以访问。</span>');

define('_AM_LABEL_COMPACTLOG', '压缩日志  ');
define('_AM_BUTTON_COMPACTLOG', '确定');
define('_AM_JS_COMPACTLOGCONFIRM', '重复的 (IP地址,类型) 记录将被删除');
define('_AM_LABEL_REMOVEALL', '删除全部记录  ');
define('_AM_BUTTON_REMOVEALL', '确定');
define('_AM_JS_REMOVEALLCONFIRM', '确认删除全部记录');
define('_AM_LABEL_REMOVE', '删除所选记录: ');
define('_AM_BUTTON_REMOVE', '确定');
define('_AM_JS_REMOVECONFIRM', '确定是否删除所选记录');
define('_AM_MSG_IPFILESUPDATED', 'IP地址记录文件已经更新');
define('_AM_MSG_BADIPSCANTOPEN', '恶意IP地址记录文件无法打开');
define('_AM_MSG_GROUP1IPSCANTOPEN', '管理员组（ group=1 ）IP地址访问配置文件无法打开');
define('_AM_MSG_REMOVED', '记录已被删除');
//define("_AM_FMT_CONFIGSNOTWRITABLE" , "设置配置文件目录: %s 属性为可写。" ) ;

// prefix_manager.php
define('_AM_H3_PREFIXMAN', '数据表前缀管理');
define('_AM_MSG_DBUPDATED', '数据库更新成功');
define('_AM_CONFIRM_DELETE', '所有数据将被删除');
define('_AM_TXT_HOWTOCHANGEDB', "如果你想改变前缀,<br /> 请手动编辑 %s/mainfile.php .<br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');");

// advisory.php
define('_AM_ADV_NOTSECURE', '不安全');

define('_AM_ADV_TRUSTPATHPUBLIC', '如果你可以看到显示 -NG- 的图片或返回页面是正常的, 说明 XOOPS_TRUST_PATH 目录位置有安全问题. 最好把 XOOPS_TRUST_PATH 目录转移到网站根目录以外.如果你不能执行这一步操作, 你必须放置一个名为 .htaccess (内容为: DENY FROM ALL)的文件在 XOOPS_TRUST_PATH 目录下.');
define('_AM_ADV_TRUSTPATHPUBLICLINK', '检测在 TRUST_PATH 目录下的文件是否安全 (你所看到的必须是404,403或500 错误提示,否则请按照下列提示调整)');

define('_AM_ADV_REGISTERGLOBALS', '该设置存在注入式攻击的漏洞.<br />If you can put .htaccess, edit or create...');
define('_AM_ADV_ALLOWURLFOPEN', '该设置允许攻击者执行远程服务器上的任意代码.<br />只有系统管理员才能修改次选项.<br />如果你是系统管理员,请编辑 php.ini 或 httpd.conf.<br /><b>例子 httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />否则, 请联系您的系统管理员.');
define('_AM_ADV_USETRANSSID', '你的session ID 会显示在锚点链接中<br />为了防止 session 拦截,在 XOOPS_ROOT_PATH 下的 .htaccess 中添加一行.<br /><b>php_flag session.use_trans_sid off</b>');
define('_AM_ADV_DBPREFIX', "该设置存在'SQL注入式'攻击漏洞.<br />不要忘记在本模块的基本参数设置中启用 '*过滤（Force sanitizing *）' 功能。");
define('_AM_ADV_LINK_TO_PREFIXMAN', '数据库表名前缀管理');
define('_AM_ADV_MAINUNPATCHED', '请按照发布说明中相关提示，配置 mainfile.php 。');

define('_AM_ADV_SUBTITLECHECK', '检查Protector模块是否正常工作');
define('_AM_ADV_CHECKCONTAMI', '变量污染');
define('_AM_ADV_CHECKISOCOM', '半个注释符号');

define('_AM_EZ_PREFIX', '前缀');
define('_AM_EZ_TABLES', '数据表');
define('_AM_EZ_UPDATED', '更新');
define('_AM_EZ_COPY', '复制');
define('_AM_EZ_ACTIONS', '操作');
define('_AM_EZ_BACKUP', '备份');
define('_AM_EZ_DELETE', '删除');
