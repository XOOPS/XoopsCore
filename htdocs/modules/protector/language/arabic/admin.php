<?php

// mymenu
define('_MD_A_MYMENU_MYTPLSADMIN', '');
define('_MD_A_MYMENU_MYBLOCKSADMIN', 'التصاريح');
define('_MD_A_MYMENU_MYPREFERENCES', 'الخيارات');

// index.php
define('_AM_TH_DATETIME', 'الوقت');
define('_AM_TH_USER', 'العضو');
define('_AM_TH_IP', 'ايبي');
define('_AM_TH_AGENT', 'العميل');
define('_AM_TH_TYPE', 'نوع العملية');
define('_AM_TH_DESCRIPTION', 'الوصف');

define('_AM_TH_BADIPS', 'الايبيهات السئية<br /><br /><span style="font-weight:normal;">اكتب كل ايبي في سطر جديد<br />اترك الصندوق فارغ ان لم ترغب بمنع اي شخص</span>');

define('_AM_TH_GROUP1IPS', 'منع اعضاء الادارة<br /><br /><span style="font-weight:normal;">اكتب كل ايبي في سطر<br />192.168. يعني 192.168.*<br />اترك الجدول فارغا ان لم ترغب بمنع اي من اعضاء الادارة</span>');

define('_AM_LABEL_COMPACTLOG', 'سجل المتكرر');
define('_AM_BUTTON_COMPACTLOG', 'حذف المتكرر');
define('_AM_JS_COMPACTLOGCONFIRM', 'سيتم حذف الايبيهات المتكررة باكثر من موضع');
define('_AM_LABEL_REMOVEALL', 'حذف كل السجلات');
define('_AM_BUTTON_REMOVEALL', 'احذف الكل');
define('_AM_JS_REMOVEALLCONFIRM', 'سيتم حذف كل السجلات نهائيا');
define('_AM_LABEL_REMOVE', 'حف المحدد من القائمة');
define('_AM_BUTTON_REMOVE', 'حذف');
define('_AM_JS_REMOVECONFIRM', 'سيتم حذف المحدد');
define('_AM_MSG_IPFILESUPDATED', 'تم تحديث  حقل الايبيهات');
define('_AM_MSG_BADIPSCANTOPEN', 'لم نتمكن من فتح ملف الايبيهات السيئة');
define('_AM_MSG_GROUP1IPSCANTOPEN', 'لم نتمكن فتح ملفات الايبيهات للادارة');
define('_AM_MSG_REMOVED', 'تم حذف السجلات');
//define("_AM_FMT_CONFIGSNOTWRITABLE" , "اعطي التصرح 777 لمجلد الكونفيج في: %s" ) ;

// prefix_manager.php
define('_AM_H3_PREFIXMAN', 'ادارة حقل القاعدة');
define('_AM_MSG_DBUPDATED', 'تم تحديث قاعدة البيانات');
define('_AM_CONFIRM_DELETE', 'سيتم حذف كل البيانات');
define('_AM_TXT_HOWTOCHANGEDB', "اذ رغبت بتغير اسم جدول قاعدة البيانات,<br /> عدل ملف  %s/mainfile.php <br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');");

// advisory.php
define('_AM_ADV_NOTSECURE', 'غير محمي');

define('_AM_ADV_TRUSTPATHPUBLIC', 'اذ كنت تشاهد الصورة بالاعلى وبها حرفان بالانجليزية فهذا يعني ان مجلد الحارس بمكان غير محمي بشكل تام. افضل شيء وضع مجلد الحارس خارج روت الموقع وان لم يكن بالامكان فيكفي  ابقاء ملف الاكسس داخل المجلد');
define('_AM_ADV_TRUSTPATHPUBLICLINK', 'تاكد من ان مجلد الحارس محمي بملف الاكسس . يجب ان تحصل على الخطا رقم 404 او 500 او 403 وان لم تحصل على من تلك الاخطاء فالمجلد اذا غير محمي بملف الاكسس');
define('_AM_ADV_REGISTERGLOBALS', 'هذة الخاصية تمكن المخربين من الحقن بقاعدة البيانات .. للحماية قم بتعطيلها من خلال ملف الاكسس بوضع الكود  التالي بالملف');
define('_AM_ADV_ALLOWURLFOPEN', 'هذا الاختيار يمكن المخربين من  تشغيل سكربتات بشكل ريموت على سيرفرك<br /><b>قم بوضع الكود التالي في ملف الاكسس لتعطيلة:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />ان كان غير مسموح لك بهذا التعديل راسل المستضيف لتعطيلة لك');
define('_AM_ADV_USETRANSSID', 'لمنع سرقة الكوكيز من جهازك او حقنه اضف هذا الكود بملف الاكسس بموقعك<br /><b>php_flag session.use_trans_sid off</b>');
define('_AM_ADV_DBPREFIX', 'هذا الخيار يتيح الحقن لقاعدة البيانات<br />لا تنسى تفعيل خيار التعقيم من خيارات الموديل');
define('_AM_ADV_LINK_TO_PREFIXMAN', 'ادارة جدول قاعدة الموقع');
define('_AM_ADV_MAINUNPATCHED', ' واضافة الكود المخصص لموديل الحارسmainfile.php عليك تعديل ملف ');
define('_AM_ADV_DBFACTORYPATCHED', 'قاعدة البيانات محمية من الحقن ');
define('_AM_ADV_DBFACTORYUNPATCHED', '    قاعدة البيانات غير محمية بماسك مانع الحقن .. عليق نقل ملف الماسك الي ملفات موقعك..  ');

define('_AM_ADV_SUBTITLECHECK', 'تاكد ان كان موديل الحارس يعمل');
define('_AM_ADV_CHECKCONTAMI', 'تلويث');
define('_AM_ADV_CHECKISOCOM', 'تعليقات متفرقة');
