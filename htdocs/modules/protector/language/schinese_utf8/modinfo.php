<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_PROTECTOR_LOADED' ) ) {

define('_MI_PROTECTOR_LOADED' , 1 ) ;

// The name of this module
define("_MI_PROTECTOR_NAME","Xoops安全卫士");

// A brief description of this module
define("_MI_PROTECTOR_DESC","该模块可以保护你的XOOPS网站免受各类攻击，比如DoS, SQL注入, 变量污染等.");

// Menu
define("_MI_PROTECTOR_ADMININDEX","管理中心");
define("_MI_PROTECTOR_ADVISORY","安全建议");
define("_MI_PROTECTOR_PREFIXMANAGER","数据表前缀管理");
//define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN','权限设置') ;

// Configs
define('_MI_PROTECTOR_GLOBAL_DISBL','暂停保护');
define('_MI_PROTECTOR_GLOBAL_DISBLDSC','所有保护将被停止。<br />别忘了，网站维护后要关闭此项。');

define('_MI_PROTECTOR_RELIABLE_IPS','信任 IP 地址');
define('_MI_PROTECTOR_RELIABLE_IPSDSC','请用 | 分隔关键词。<br />^aaa.bbb.ccc 将拒绝以aaa.bbb.ccc开头的IP地址的访问。<br />
aaa.bbb.ccc$ 将允许以aaa.bbb.ccc结尾的IP地址的访问。<br />
aaa.bbb.ccc 将允许包含aaa.bbb.ccc的IP地址的访问。');

define('_MI_PROTECTOR_LOG_LEVEL','日志级别');
//define('_MI_PROTECTOR_LOG_LEVELDSC','');

define('_MI_PROTECTOR_BANIP_TIME0','恶意 IP 屏蔽时间 (秒)');

define('_MI_PROTECTOR_LOGLEVEL0','无');
define('_MI_PROTECTOR_LOGLEVEL15','Quiet');
define('_MI_PROTECTOR_LOGLEVEL63','quiet');
define('_MI_PROTECTOR_LOGLEVEL255','全部');

define('_MI_PROTECTOR_HIJACK_TOPBIT','保护 IP 位数');
define('_MI_PROTECTOR_HIJACK_TOPBITDSC',' Session 欺骗拦截:<br />默认 32(位). (每一位都受到保护)<br />当你的 IP 不稳定时, 可以设置一个 IP 段的位数.<br />(如)如果你的IP可以在这个范围里面切换 192.168.0.0-192.168.0.255, 在这个选项中设置为 24(bit)');
define('_MI_PROTECTOR_HIJACK_DENYGP','不允许将 IP 地址转移到 session中的群组');
define('_MI_PROTECTOR_HIJACK_DENYGPDSC','反 Session 拦截:<br />选中的群组将不能把当前的IP转移到session中。<br />(推荐管理员群组启用.)');
define('_MI_PROTECTOR_SAN_NULLBYTE','空字符过滤');
define('_MI_PROTECTOR_SAN_NULLBYTEDSC','这个空字符 "\\0" 经常用于恶意攻击<br />启用此功能后空字符将会被转换成空格。<br />(高度推荐启用)');
define('_MI_PROTECTOR_DIE_NULLBYTE','如果发现空字符攻击后停用模块');
define('_MI_PROTECTOR_DIE_NULLBYTEDSC','这个空字符 "\\0" 经常用于恶意攻击.<br />(高度推荐启用)');
define('_MI_PROTECTOR_DIE_BADEXT','如果有恶意文件上传后停用模块');
define('_MI_PROTECTOR_DIE_BADEXTDSC','如果有人尝试上传特殊扩展名的文件如 .php 等, 这个模块将被停用.<br />如果经常要添加 php 文件到 B-Wiki 或者 PukiWikiMod, 请关闭此项.');
define('_MI_PROTECTOR_CONTAMI_ACTION','如果发现全局变量污染现象后的行为');
define('_MI_PROTECTOR_CONTAMI_ACTIONDS','选择一个行为，当有人试图污染全局变量时候执行这个行为。<br />(推荐选择白屏)');
define('_MI_PROTECTOR_ISOCOM_ACTION','如果发现半个注释符号后的行为');
define('_MI_PROTECTOR_ISOCOM_ACTIONDSC','SQL注入拦截:<br />选择一个行为， 在发现半个注释符号"/*"后执行的行为.<br />"转换" 就是把缺少的一半注释符号 "*/" 补全.<br />(推荐选择转换)');
define('_MI_PROTECTOR_UNION_ACTION','如果发现请求中带有 “UNION” 后的行为');
define('_MI_PROTECTOR_UNION_ACTIONDSC','SQL注入拦截:<br />选择一个行为，比如在SQL中出现 UNION 后执行的行为.<br />"转换" 就是把 "union" 改为 "uni-on".<br />(推荐选择转换)');
define('_MI_PROTECTOR_ID_INTVAL','强制初始化变量类型为整数');
define('_MI_PROTECTOR_ID_INTVALDSC','所有名为 "*id" 的请求将被转换为整数。<br />这个选项保护你的网站不会受到SQL注入和跨站攻击（XSS）。<br />推荐启用此功能, 但是可能会导致一些模块出现问题.');
define('_MI_PROTECTOR_FILE_DOTDOT','目录系统保护');
define('_MI_PROTECTOR_FILE_DOTDOTDSC','此功能将会去除请求中的 ".." 如目录遍历 ');

define('_MI_PROTECTOR_BF_COUNT','反暴力破解');
define('_MI_PROTECTOR_BF_COUNTDSC','设置最多重试登录时间为10分钟。如果有人失败的时间超过了这个时间限制，他/她的IP地址将被禁止。');

define('_MI_PROTECTOR_DOS_SKIPMODS','不检测 DDoS/（爬虫）Crawler 的模块');
define('_MI_PROTECTOR_DOS_SKIPMODSDSC','设置模块的目录以“|”隔开. 这个选项是非常有用的，比如聊天室模块等等。');

define('_MI_PROTECTOR_DOS_EXPIRE','高频重载监控时间 (秒)');
define('_MI_PROTECTOR_DOS_EXPIREDSC','高频重载(F5攻击)和高负荷爬虫的监控时间.');

define('_MI_PROTECTOR_DOS_F5COUNT','F5攻击的次数限值');
define('_MI_PROTECTOR_DOS_F5COUNTDSC','防止DoS攻击.<br />如果重载次数超过该值, 将被当作有害攻击处理.');
define('_MI_PROTECTOR_DOS_F5ACTION','针对F5的措施');

define('_MI_PROTECTOR_DOS_CRCOUNT','爬虫的次数限值');
define('_MI_PROTECTOR_DOS_CRCOUNTDSC','防止高负荷爬虫.<br />如果重载次数超过该值, 将被当作有害爬虫.');
define('_MI_PROTECTOR_DOS_CRACTION','针对高负荷爬虫的措施');

define('_MI_PROTECTOR_DOS_CRSAFE','受欢迎的User-Agent');
define('_MI_PROTECTOR_DOS_CRSAFEDSC','User-Agent的perl正则表达式.<br />如果符合该表达式, 该爬虫将不再当作高负荷爬虫处理.<br />例如 /(Baidu|msnbot|Googlebot|Yahoo! Slurp)/i');

define('_MI_PROTECTOR_OPT_NONE','否 (只记录)');
define('_MI_PROTECTOR_OPT_SAN','转换');
define('_MI_PROTECTOR_OPT_EXIT','白屏');
define('_MI_PROTECTOR_OPT_BIP','封IP (永久)');
define('_MI_PROTECTOR_OPT_BIPTIME0','封IP (暂时)');

define('_MI_PROTECTOR_DOSOPT_NONE','否 (只记录)');
define('_MI_PROTECTOR_DOSOPT_SLEEP','休眠');
define('_MI_PROTECTOR_DOSOPT_EXIT','白屏');
define('_MI_PROTECTOR_DOSOPT_BIP','封IP (永久)');
define('_MI_PROTECTOR_DOSOPT_BIPTIME0','封IP (延期)');
define('_MI_PROTECTOR_DOSOPT_HTA','DENY by .htaccess');

define('_MI_PROTECTOR_BIP_EXCEPT','不被记录为恶意IP地址的群组');
define('_MI_PROTECTOR_BIP_EXCEPTDSC','属于这些群组的用户将永远不会被禁止。<br />(推荐管理员群组启用此项)');

define('_MI_PROTECTOR_DISABLES','禁用XOOPS的危险特性');

define('_MI_PROTECTOR_BIGUMBRELLA','跨站攻击拦截 (BigUmbrella)');
define('_MI_PROTECTOR_BIGUMBRELLADSC','此功能可以保护你的网站不会受到由XSS漏洞导致的攻击。但是不能够 100% 的拦截。');

define('_MI_PROTECTOR_SPAMURI4U','反垃圾邮件（anti-SPAM）: URLs for normal users');
define('_MI_PROTECTOR_SPAMURI4UDSC','If this number of URLs are found in POST data from users other than admin, the POST is considered as SPAM. 填0表示禁用此功能.');
define('_MI_PROTECTOR_SPAMURI4G','反垃圾邮件（anti-SPAM）: URLs for guests');
define('_MI_PROTECTOR_SPAMURI4GDSC','If this number of URLs are found in POST data from guests, the POST is considered as SPAM. 填0表示禁用此功能.');

}

?>