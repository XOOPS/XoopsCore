<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if ( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_PROTECTOR_LOADED' ) ) {

define('_MI_PROTECTOR_LOADED' , 1 ) ;

// The name of this module
define("_MI_PROTECTOR_NAME","ÇáÍÇÑÓ áÒæÈÓ");

// A brief description of this module
define("_MI_PROTECTOR_DESC","åÐÇ ÇáÈÑäÇãÌ íæÝÑ áãæÞÚß ÇáÍãÇíÉ ãä ÚãáíÇÊ ÇáÇÎÊÑÇÞ ÇáãÎÊáÝÉ áãæÞÚß");

// Menu
define("_MI_PROTECTOR_ADMININDEX","ÇáÑÆíÓíÉ");
define("_MI_PROTECTOR_ADVISORY","ÊÝÍÕ ÇáÍãÇíÉ");
define("_MI_PROTECTOR_PREFIXMANAGER","ÇÏÇÑÉ ÌÏæá ÞÇÚÏÉ ÇáÈíÇäÇÊ");
//define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN','ÇáÊÕÇÑíÍ') ;

// Configs
define('_MI_PROTECTOR_GLOBAL_DISBL','ÊÚØíá ÇáãæÏíá');
define('_MI_PROTECTOR_GLOBAL_DISBLDSC','ÊÚØíá ÈÑäÇãÌ ÇáÍÇÑÓ ');

define('_MI_PROTECTOR_DEFAULT_LANG','ÇááÛÉ');
define('_MI_PROTECTOR_DEFAULT_LANGDSC','common.php ÍÏÏ ÇááÛÉ ÇáÊí ÓÊÓÊÚãá ÞÈá ØáÈ ãáÝ  ');

define('_MI_PROTECTOR_RELIABLE_IPS','ÇáÇíÈíåÇÊ ÇáÕÏíÞÉ');
define('_MI_PROTECTOR_RELIABLE_IPSDSC',' |ÖÚ ÇáÇíÈíåÇÊ ÇáÊí ÊÚÊÈÑ ÕÏíÞÉ æíãßä ÇáÇÚÊãÇÏ ÚáíÉ ÇÝÕá ÇáÇíÈíåÇÊ ÈåÐÉ ÇáÇÔÇÑÉ');

define('_MI_PROTECTOR_LOG_LEVEL','ÍÝÙ ÇáÓÌáÇÊ');
//define('_MI_PROTECTOR_LOG_LEVELDSC','');

define('_MI_PROTECTOR_BANIP_TIME0','ãÏÉ ÇáãäÚ ááÇíÈí ÇáãÍÖæÑ - ÈÇáËæÇäí)');

define('_MI_PROTECTOR_LOGLEVEL0','ÈÏæä');
define('_MI_PROTECTOR_LOGLEVEL15','ÚÇÏí');
define('_MI_PROTECTOR_LOGLEVEL63','ÚÇÏí');
define('_MI_PROTECTOR_LOGLEVEL255','Çáßá');

define('_MI_PROTECTOR_HIJACK_TOPBIT','ÍãÇíÉ ÇáÇíÈí ÇËäÇÁ ÇáÌáÓå-Çí ÇáÊæÇÌÏ ÈÇáãæÞÚ');
define('_MI_PROTECTOR_HIJACK_TOPBITDSC','ÇáÍãÇíÉ ááÇíÈí ãä ÓÑÞÉ ÇáßæßíÒ  . ÇÐ ßÇä áß ÇíÈí ËÇÈÊ ÇÎÊÇÑ 32 ÇÐ ßÇä ÛíÑ ËÇÈÊ ÇÎÊÇÑ 24 ßÇÝÊÑÇÖí');
define('_MI_PROTECTOR_HIJACK_DENYGP','ÇáãÌãæÚÇÊ ÇáÛíÑ ãÓãæÍ ÈäÞáåÇ Çáí äÙÇã ÍãÇíÉ ÇáÌáÓÉ');
define('_MI_PROTECTOR_HIJACK_DENYGPDSC','ãÇäÚ ÍÞä æÓÑÞÉ ÇáßæßíÒ Ýí ÇáÌáÓÉ:<br />ÇÎÊÇÑ ÇáãÌãæÚÉ ÇáÛíÑ ãÓãæÍ áåÇ ÈÇáÇäÊÞÇá ÊÍÊ äÙÇã ÇáÍãÇíÉ ÇËäÇÁ ÇáÌáÓÉ . ãä ÇáãÞÊÑÍ ÇÎÊíÇÑ ãÌãæÚÉ ÇáÇÏÇÑÉ');
define('_MI_PROTECTOR_SAN_NULLBYTE','null-bytes ÇáÊÚÞíã áÇæÇãÑ ãä äæÚ');
define('_MI_PROTECTOR_SAN_NULLBYTEDSC','"\\0" ãä ÇáãÞÊÑÍ ÊÝÚíá åÐÇ ÇáÎíÇÑ áÇä åÐÇ ÇáßæÏ ÛÇáÈÇ ãÇ íÓÊÎÏã Ýí ÚãáíÇÊ ÇáÊÎÑíÈ');
define('_MI_PROTECTOR_DIE_NULLBYTE','"\\0" ÇáÎÑæÌ Ýí ÍÇáÉ æÌæÏ  ÚãáíÉ ãä äæÚ äíá ÈÇÊÓ');
define('_MI_PROTECTOR_DIE_NULLBYTEDSC','"\\0" ãä ÇáãÞÊÑÍ ÊÝÚíá åÐÇ ÇáÎíÇÑ áÇä åÐÇ ÇáßæÏ ÛÇáÈÇ ãÇ íÓÊÎÏã Ýí ÚãáíÇÊ ÇáÊÎÑíÈ');
define('_MI_PROTECTOR_DIE_BADEXT','ÇáÎÑæÌ Ýí ÍÇáÉ ÑÝÚ ãáÝ ÓíÁ');
define('_MI_PROTECTOR_DIE_BADEXTDSC','ÇÐ ÍÇæá ÇÍÏ ÑÝÚ ãáÝ ÈÕíÛÉ Èí ÇÊÔ Èí  Çæ ÕíÛÉ ÇÎÑí ÛíÑ ãÓãæÍ ÈåÇ<br />ÇÐ ßäÊ Ýí ÇáÛÇáÈ ÊÑÝÚ ãáÝÇÊ ÈÕíÛÉ Èí ÇÊÔ Èí ÝÞã ÇÐ ÈÊÚØíá åÐÇ ÇáÎíÇÑ ');
define('_MI_PROTECTOR_CONTAMI_ACTION','ãÍÇæáÉ ÊáæíË æÇáÚÈË ÈãÊÛíÑÇÊ ÇáãÌáÉ');
define('_MI_PROTECTOR_CONTAMI_ACTIONDS','ÇÎÊÇÑ ÇáÚãá Ýí ÍÇáÉ ÇßÊÔÇÝ ãÍÇæáÉ áÊáæíË  æÇáÚÈË ÈãÊÛíÑÇÊ ÇáãÌáÉ ÇáÚÇãÉ<br />ÇáãÞÊÑÍ åæ  ÇÎÊíÇÑ ÕÝÍÉ ÈíÖÇÁ');
define('_MI_PROTECTOR_ISOCOM_ACTION','ÇáÚãá ÍÇá ÇßÊÔÇÝ ÊÚáíÞ ãáÛæã');
define('_MI_PROTECTOR_ISOCOM_ACTIONDSC','ãÇäÚ ÇáÍÞ Ýí ÇáÞÇÚÏå:<br />"/*" ÇáÚãá ÍÇá ÇßÊÔÇÝÉ åÐÇ ÇáÑãÒ Ýí ÊÚáíÞ ãÇ<br />ÇáÊÚÞíã íÚäí ÇÖÇÝÉ ÑãÒ ÇáÓáÇÔ ááßæÏ áÊÚØíáÉ - ÇáÚãá ÇáãÞÊÑÍ  åæ ÇÎÊíÇÑ ÊÚÞíã ÇáÇãÑ');
define('_MI_PROTECTOR_UNION_ACTION','ÇáÚãá ÍÇá ÇßÊÔÇÝ Çí ãä ÇæÇãÑ ÇáÇÊÍÇÏ');
define('_MI_PROTECTOR_UNION_ACTIONDSC','ãÇäÚ ÇáÍÞä ááÞÇÚÏÉ:<br />ÇÎÊÇÑ ÇáÚãá ÍÇá ÇßÊÔÇÝ Çí ÚãáíÉ ÎÇÑÌíÉ ãä ÚãáíÇÊ ÇáÇÊÍÇÏ æÇáÚãá ÇáãÞÊÑÍ åæ ÊÚÞíã ÇáÇãÑ<br />""union" ÓíÊã ÊÛíÑ ÇáÑãÒ ÈæÖÚ ÏÇÔ  ÈãäÊÕÝ ÇáßáãÉ');
define('_MI_PROTECTOR_ID_INTVAL','ID ÇæÇãÑ ÇáØáÈ æÇáÌáÈ ãä ÇáÞÇÚÏÉ');
define('_MI_PROTECTOR_ID_INTVALDSC','"*id" ßá ÇáÇæÇãÑ ÇáÊí ÊäÊåí ÈåÐÇ ÇáÑãÒ<br />ÊÝÚíá ÇáÎíÇÑ íÍãí ãä ÈÚÖ ÚãáíÇÊ ÇáÍÞ<br />åÐÇ ÇáÇÎÊíÇÑ íÓÈÈ ÇÍíÇäÇ ÈÊÚØá ÈÑÇãÌ ÇÎÑí áÐáß ßã ÈÊÚØíáÉ  ÇáÇ ÇÐ ßäÊ ÊÚÑÝ ãÇ ÊÝÚá');
define('_MI_PROTECTOR_FILE_DOTDOT','Directory TraversalsÇáãäÚ ãä ÚãáíÇÊ ÇáÊäÞá ');
define('_MI_PROTECTOR_FILE_DOTDOTDSC','ãäÚ ßá ÇáÚãáíÇÊ ÇáÊí ÊÈÏæ  Úáì ÇäåÇ ÊÞæã ÈÇÓÊÚÑÇÖ ÇáãæÞÚ æÇáãáÝÇÊ æÇáÊí ÊÈÍË Úä ËÛÑÇÊ ÈÇáãæÞÚ');

define('_MI_PROTECTOR_BF_COUNT','ãÇäÚ ãÍÇæáÉ ÊÓÌíá ÇáÏÎæá ÇáãÊßÑÑå');
define('_MI_PROTECTOR_BF_COUNTDSC','ÍÏÏ ÚÏÏ ÇáãÑÇÊ ÇáãÓãæÍ ááÚÖæ ÈåÇ áÊÓÌíá ÏÎæáÉ ÈßáãÉ ÓÑ ÛíÑ ÕÍíÍÉ æÈÚÏ ÇáÚÏÏ ÇáãÍÏÏ ÓíÊã ØÑÏÉ');

define('_MI_PROTECTOR_BWLIMIT_COUNT','ÊÍÏíÏ æÖÈØ ÍÌã ÊÈÇÏá ÇáãáÝÇÊ - ÇáÈÇäÏæíÏË');
define('_MI_PROTECTOR_BWLIMIT_COUNTDSC','mainfile.php ÖÚ ÕÝÑ ááãæÇÞÚ ÇáÊí áÏíåÇ ÞÏÑå ÌíÏå Úáì ÇÓÊíÚÇÈ ÚÏÏ áÇÈÇÓ Èå ãä ÇáÒæÇÑ  æÇí ÑÞã ÇÞá ãä 10 ÓíÊã ÊÌÇåáÉ -ÍÏÏ ÚÏÏ ÇáãÑÇÊ ÇáÊí íÓÊØíÚ ÇáÒÇÆÑ ÝíåÇ ÒíÇÑÉ ãáÝ');

define('_MI_PROTECTOR_DOS_SKIPMODS',' Crawler ÇáÈÑÇãÌ ÇáÛíÑ ÎÇÖÚÉ áäÙÇã ÇáãÑÇÞÈÉ');
define('_MI_PROTECTOR_DOS_SKIPMODSDSC','|Þã ÈßÊÇÈÉ ÇÓãÇÁ ÇáãæÏíáÇÊ ÇáÊí ÓíÊã ÇÓÊËäÇÁåÇ ãä ÇáãÑÇÞÈÉ  ÇÝÕá Èíä ÇáÈÑÇãÌ ÈÇáÇÔÇÑå');

define('_MI_PROTECTOR_DOS_EXPIRE','ãÑÇÞÈÉ ÇáÖÛØ Úáì ÇáãæÞÚ ÈÇáËæÇäí');
define('_MI_PROTECTOR_DOS_EXPIREDSC','F5åÐÇ ÇáÇÎÊíÇÑ áãÑÇÞÈÉ ÇáÖÛØ ÇáãÍÏË Úáì ÇáãæÞÚ ãä ÎáÇá ÈÑÇãÌ ÇáÈÍË ãËáÇ Çæ ÍÇá ÇÓÊÎÏÇã äÙÇã ÊÍÏíË Çæ ÑíÝÑíÔ ÇáãæÞÚ ÈÇÓÊÎÏÇã ÇáÇÏÇÉ ');

define('_MI_PROTECTOR_DOS_F5COUNT',' F5ÚÏÏ ÇáãÑÇÊ áÇÍÊÓÇÈåÇ åÌæã');
define('_MI_PROTECTOR_DOS_F5COUNTDSC','ááÍãíÇÉ ãä  ÇáÏæÓ æÇÓÊäÒÇÝ ÇáãæÞÚ ÈÇÚÇÏÉ ÊÍãíá ÕÝÍÉ ÇáÈÏÇíÉ ÇßËÑ ãä ãÑå');
define('_MI_PROTECTOR_DOS_F5ACTION',' F5 ÇáÚãá ÍÇá ÇßÊÔÇÝ åÌæã ãä äæÚ');

define('_MI_PROTECTOR_DOS_CRCOUNT','ÚÏÏ ãÑÇÊ ÇáÇÓÊÚÑÇÖ ãä ÞÈá ãÍÑßÇÊ ÇáÈÍË ÞÈá ÇÚÊÈÇÑ ÇáÚãáíÉ åÌæã');
define('_MI_PROTECTOR_DOS_CRCOUNTDSC','ááãäÚ ãä ßá ÇáÚãáíÇÊ ÇáÊí ÊÞæã ÈãÍÇæáå ÇÓÊÚÑÇÖ ßá ãáÝÇÊ æÑÇæÈØ ãæÞÚß æÇÍÏÇË ÖÛØ ÚáíÉ');
define('_MI_PROTECTOR_DOS_CRACTION','ÇáÚãá ÍÇá ÇßÊÔÇÝ ÚãáíÇÊ ÇäÔÇÁ ÖÛØ ÚÇáí Úáì ÇáãæÞÚ');

define('_MI_PROTECTOR_DOS_CRSAFE','ãÍÑßÇÊ ÇáÈÍË ÇáãÓãæÍ áåÇ ');
define('_MI_PROTECTOR_DOS_CRSAFEDSC','ßá ãÍÑßÇÊ ÇáÈÍË ÇáãÖÇÝÉ ÈÇáÍÞá áä ÊÚÊÈÑ ãÍÑßÇÊ ÈÍË ÓíÆÉ Çæ ÊÍÏË ÖÛØ Úáì ÇáãæÞÚ<br />ãËá<br />eg) /(msnbot|Googlebot|Yahoo! Slurp)/i');

define('_MI_PROTECTOR_OPT_NONE','áÇÔíÁ ÝÞØ ÓÌá ÇáÚãáíÉ');
define('_MI_PROTECTOR_OPT_SAN','ÊÚÞíã ÇáÇãÑ');
define('_MI_PROTECTOR_OPT_EXIT','ÕÝÍÉ ÈíÖÇÁ');
define('_MI_PROTECTOR_OPT_BIP','ãäÚ ÇáÇíÈí ááÇÈÏ');
define('_MI_PROTECTOR_OPT_BIPTIME0','ãäÚ ÇáÇíÈí ãÄÞÊ');

define('_MI_PROTECTOR_DOSOPT_NONE','áÇÔíÁ ÝÞØ ÓÌá ÇáÚãáíÉ');
define('_MI_PROTECTOR_DOSOPT_SLEEP','ÚÏã ÇÓÊÌÇÈÉ-äÇÆã');
define('_MI_PROTECTOR_DOSOPT_EXIT','ÕÝÍÉ ÈíÖÇÁ');
define('_MI_PROTECTOR_DOSOPT_BIP','ãäÚ ÇáÇíÈí ááÇÈÏ');
define('_MI_PROTECTOR_DOSOPT_BIPTIME0','ãäÚ ÇáÇíÈí ãÄÞÊ');
define('_MI_PROTECTOR_DOSOPT_HTA','.htaccess ÇáãäÚ ÈãáÝ');

define('_MI_PROTECTOR_BIP_EXCEPT','ÇáãÌãæÚÉ  ÇáÊí áÇ íÊã ØÑÏåÇ ÇÈÏÇ');
define('_MI_PROTECTOR_BIP_EXCEPTDSC','ÍÏÏ ÇíÈí ãÚíä   áÍãÇíÊå ãä ÇáØÑÏ ãä ÇáãæÞÚ<br />(ãä ÇáãÞÊÑÍ ÝÞØ ÇíÈí ÇáãÏíÑ');

define('_MI_PROTECTOR_DISABLES','XOOPS ÊÚØíá  ÎÕÇÆÕ ÎØíÑÉ Ýí ãÌáÉ');

define('_MI_PROTECTOR_DBLAYERTRAP','ÊÝÚíá ÇáÞäÇÚ áÖÈØ ÚãáíÇÊ ÇáÍÞä');
define('_MI_PROTECTOR_DBLAYERTRAPDSC','åÐÇ ÇáÇÎÊíÇÑ íãäÚ ÇáÚÏíÏ ãä ÚãáíÇÊ ÇáÍÞä . æáßä Úáíß ÇáÊÇßÏ ãä ÊÝÍÕ ÇáÍãÇíÉ áãÚÑÝÉ ãÇ Çä ßÇä áÏíß ÇáãÇÓß Çæ ÇáÞäÇÚ');
define('_MI_PROTECTOR_DBTRAPWOSRV','áÇÊÞã ÇÈÏ ÈÊÝÍÕ ÇáÓíÑÝÑ ãä ãÇäÚ ÇáÍÞä');
define('_MI_PROTECTOR_DBTRAPWOSRVDSC',' åäÇß ÓíÑÝÑÇÊ áÏíåÇ äÙÇã ãÇäÚ ááÍÞä Ýí ÞÇÚÏÉ ÇáÈíÇäÇÊ - áæ æÇÌåÊ ãÔßáÉ ÈãæÞÚß Þã ÈÊÝÚíá åÐÇ ÇáÇÎÊíÇÑ');

define('_MI_PROTECTOR_BIGUMBRELLA','anti-XSS (BigUmbrella)ÇáÍãÇíÉ ãä ÇáåÌæã ãä äæÚ');
define('_MI_PROTECTOR_BIGUMBRELLADSC','åÐÇ ÇáäæÚ íÞæã ÇáãåÇÌã ÈÇÑÓÇá ãÍÊæì ãä ÎáÇáÉ íÍÇæá ÓÑÞÉ ÇÑÞÇã ÍÓÇÈÇÊ æÇíãíáÇÊ æÇí ÈíÇäÇÊ ÍÓÇÓÉ ãä ãæÞÚ ÇáÖÍíÉ. ÇáÍÇÑÓ áÇíæÝÑ ÍãÇíÉ ßÇãáÉ áåÐÇ ÇáäæÚ  áÇÎÊáÇÝ ÇäæÇÚ ÇáåÌæã ');

define('_MI_PROTECTOR_SPAMURI4U','ãÇäÚ ÇáÓÈÇã ááÇÚÖÇÁ');
define('_MI_PROTECTOR_SPAMURI4UDSC','Çí ãæÖæÚ Çæ ÊÚáíÞ ãä ÞÈá ÇáÇÚÖÇÁ íÍÊæí åÐÇ ÇáÚÏÏ ãä ÇáÑæÇÈØ ÓíÚÊÈÑ ÓÈÇã æÖÚß ÕÝÑ íÚäí ÊÚØíá ÇáÇÎÊíÇÑ');
define('_MI_PROTECTOR_SPAMURI4G','ãÇäÚ ÇáÓÈÇã ááÒæÇÑ');
define('_MI_PROTECTOR_SPAMURI4GDSC','Çí ãæÖæÚ Çæ ÊÚáíÞ íÍÊæí åÐÇ ÇáÚÏÏ ãä ÇáÑæÇÈØ ÓíÚÊÈÑ ÓÈÇã æÖÚ ÕÝÑ íÚäí ÊÚØíáß ááÇÎÊíÇÑ');

}
