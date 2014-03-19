<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( $constpref.'_LOADED' ) ) {

define( $constpref.'_LOADED' , 1 ) ;

// The name of this module
define($constpref."_NAME","الحارس لزوبس");

// A brief description of this module
define($constpref."_DESC","هذا البرنامج يوفر لموقعك الحماية من عمليات الاختراق المختلفة لموقعك");

// Menu
define($constpref."_ADMININDEX","الرئيسية");
define($constpref."_ADVISORY","تفحص الحماية");
define($constpref."_PREFIXMANAGER","ادارة جدول قاعدة البيانات");
define($constpref.'_ADMENU_MYBLOCKSADMIN','التصاريح') ;

// Configs
define($constpref.'_GLOBAL_DISBL','تعطيل الموديل');
define($constpref.'_GLOBAL_DISBLDSC','تعطيل برنامج الحارس ');

define($constpref.'_DEFAULT_LANG','اللغة');
define($constpref.'_DEFAULT_LANGDSC','common.php حدد اللغة التي ستستعمل قبل طلب ملف  ');

define($constpref.'_RELIABLE_IPS','الايبيهات الصديقة');
define($constpref.'_RELIABLE_IPSDSC',' |ضع الايبيهات التي تعتبر صديقة ويمكن الاعتماد علية افصل الايبيهات بهذة الاشارة');

define($constpref.'_LOG_LEVEL','حفظ السجلات');
define($constpref.'_LOG_LEVELDSC','');

define($constpref.'_BANIP_TIME0','مدة المنع للايبي المحضور - بالثواني)');

define($constpref.'_LOGLEVEL0','بدون');
define($constpref.'_LOGLEVEL15','عادي');
define($constpref.'_LOGLEVEL63','عادي');
define($constpref.'_LOGLEVEL255','الكل');

define($constpref.'_HIJACK_TOPBIT','حماية الايبي اثناء الجلسه-اي التواجد بالموقع');
define($constpref.'_HIJACK_TOPBITDSC','الحماية للايبي من سرقة الكوكيز  . اذ كان لك ايبي ثابت اختار 32 اذ كان غير ثابت اختار 24 كافتراضي');
define($constpref.'_HIJACK_DENYGP','المجموعات الغير مسموح بنقلها الي نظام حماية الجلسة');
define($constpref.'_HIJACK_DENYGPDSC','مانع حقن وسرقة الكوكيز في الجلسة:<br />اختار المجموعة الغير مسموح لها بالانتقال تحت نظام الحماية اثناء الجلسة . من المقترح اختيار مجموعة الادارة');
define($constpref.'_SAN_NULLBYTE','null-bytes التعقيم لاوامر من نوع');
define($constpref.'_SAN_NULLBYTEDSC','"\\0" من المقترح تفعيل هذا الخيار لان هذا الكود غالبا ما يستخدم في عمليات التخريب');
define($constpref.'_DIE_NULLBYTE','"\\0" الخروج في حالة وجود  عملية من نوع نيل باتس');
define($constpref.'_DIE_NULLBYTEDSC','"\\0" من المقترح تفعيل هذا الخيار لان هذا الكود غالبا ما يستخدم في عمليات التخريب');
define($constpref.'_DIE_BADEXT','الخروج في حالة رفع ملف سيء');
define($constpref.'_DIE_BADEXTDSC','اذ حاول احد رفع ملف بصيغة بي اتش بي  او صيغة اخري غير مسموح بها<br />اذ كنت في الغالب ترفع ملفات بصيغة بي اتش بي فقم اذ بتعطيل هذا الخيار ');
define($constpref.'_CONTAMI_ACTION','محاولة تلويث والعبث بمتغيرات المجلة');
define($constpref.'_CONTAMI_ACTIONDS','اختار العمل في حالة اكتشاف محاولة لتلويث  والعبث بمتغيرات المجلة العامة<br />المقترح هو  اختيار صفحة بيضاء');
define($constpref.'_ISOCOM_ACTION','العمل حال اكتشاف تعليق ملغوم');
define($constpref.'_ISOCOM_ACTIONDSC','مانع الحق في القاعده:<br />"/*" العمل حال اكتشافة هذا الرمز في تعليق ما<br />التعقيم يعني اضافة رمز السلاش للكود لتعطيلة - العمل المقترح  هو اختيار تعقيم الامر');
define($constpref.'_UNION_ACTION','العمل حال اكتشاف اي من اوامر الاتحاد');
define($constpref.'_UNION_ACTIONDSC','مانع الحقن للقاعدة:<br />اختار العمل حال اكتشاف اي عملية خارجية من عمليات الاتحاد والعمل المقترح هو تعقيم الامر<br />""union" سيتم تغير الرمز بوضع داش  بمنتصف الكلمة');
define($constpref.'_ID_INTVAL','ID اوامر الطلب والجلب من القاعدة');
define($constpref.'_ID_INTVALDSC','"*id" كل الاوامر التي تنتهي بهذا الرمز<br />تفعيل الخيار يحمي من بعض عمليات الحق<br />هذا الاختيار يسبب احيانا بتعطل برامج اخري لذلك كم بتعطيلة  الا اذ كنت تعرف ما تفعل');
define($constpref.'_FILE_DOTDOT','Directory Traversalsالمنع من عمليات التنقل ');
define($constpref.'_FILE_DOTDOTDSC','منع كل العمليات التي تبدو  على انها تقوم باستعراض الموقع والملفات والتي تبحث عن ثغرات بالموقع');

define($constpref.'_BF_COUNT','مانع محاولة تسجيل الدخول المتكرره');
define($constpref.'_BF_COUNTDSC','حدد عدد المرات المسموح للعضو بها لتسجيل دخولة بكلمة سر غير صحيحة وبعد العدد المحدد سيتم طردة');

define($constpref.'_BWLIMIT_COUNT','تحديد وضبط حجم تبادل الملفات - الباندويدث');
define($constpref.'_BWLIMIT_COUNTDSC','mainfile.php ضع صفر للمواقع التي لديها قدره جيده على استيعاب عدد لاباس به من الزوار  واي رقم اقل من 10 سيتم تجاهلة -حدد عدد المرات التي يستطيع الزائر فيها زيارة ملف');

define($constpref.'_DOS_SKIPMODS',' Crawler البرامج الغير خاضعة لنظام المراقبة');
define($constpref.'_DOS_SKIPMODSDSC','|قم بكتابة اسماء الموديلات التي سيتم استثناءها من المراقبة  افصل بين البرامج بالاشاره');

define($constpref.'_DOS_EXPIRE','مراقبة الضغط على الموقع بالثواني');
define($constpref.'_DOS_EXPIREDSC','F5هذا الاختيار لمراقبة الضغط المحدث على الموقع من خلال برامج البحث مثلا او حال استخدام نظام تحديث او ريفريش الموقع باستخدام الاداة ');

define($constpref.'_DOS_F5COUNT',' F5عدد المرات لاحتسابها هجوم');
define($constpref.'_DOS_F5COUNTDSC','للحمياة من  الدوس واستنزاف الموقع باعادة تحميل صفحة البداية اكثر من مره');
define($constpref.'_DOS_F5ACTION',' F5 العمل حال اكتشاف هجوم من نوع');

define($constpref.'_DOS_CRCOUNT','عدد مرات الاستعراض من قبل محركات البحث قبل اعتبار العملية هجوم');
define($constpref.'_DOS_CRCOUNTDSC','للمنع من كل العمليات التي تقوم بمحاوله استعراض كل ملفات وراوبط موقعك واحداث ضغط علية');
define($constpref.'_DOS_CRACTION','العمل حال اكتشاف عمليات انشاء ضغط عالي على الموقع');

define($constpref.'_DOS_CRSAFE','محركات البحث المسموح لها ');
define($constpref.'_DOS_CRSAFEDSC','كل محركات البحث المضافة بالحقل لن تعتبر محركات بحث سيئة او تحدث ضغط على الموقع<br />مثل<br />eg) /(msnbot|Googlebot|Yahoo! Slurp)/i');

define($constpref.'_OPT_NONE','لاشيء فقط سجل العملية');
define($constpref.'_OPT_SAN','تعقيم الامر');
define($constpref.'_OPT_EXIT','صفحة بيضاء');
define($constpref.'_OPT_BIP','منع الايبي للابد');
define($constpref.'_OPT_BIPTIME0','منع الايبي مؤقت');

define($constpref.'_DOSOPT_NONE','لاشيء فقط سجل العملية');
define($constpref.'_DOSOPT_SLEEP','عدم استجابة-نائم');
define($constpref.'_DOSOPT_EXIT','صفحة بيضاء');
define($constpref.'_DOSOPT_BIP','منع الايبي للابد');
define($constpref.'_DOSOPT_BIPTIME0','منع الايبي مؤقت');
define($constpref.'_DOSOPT_HTA','.htaccess المنع بملف');

define($constpref.'_BIP_EXCEPT','المجموعة  التي لا يتم طردها ابدا');
define($constpref.'_BIP_EXCEPTDSC','حدد ايبي معين   لحمايته من الطرد من الموقع<br />(من المقترح فقط ايبي المدير');

define($constpref.'_DISABLES','XOOPS تعطيل  خصائص خطيرة في مجلة');

define($constpref.'_DBLAYERTRAP','تفعيل القناع لضبط عمليات الحقن');
define($constpref.'_DBLAYERTRAPDSC','هذا الاختيار يمنع العديد من عمليات الحقن . ولكن عليك التاكد من تفحص الحماية لمعرفة ما ان كان لديك الماسك او القناع');
define($constpref.'_DBTRAPWOSRV','لاتقم ابد بتفحص السيرفر من مانع الحقن');
define($constpref.'_DBTRAPWOSRVDSC',' هناك سيرفرات لديها نظام مانع للحقن في قاعدة البيانات - لو واجهت مشكلة بموقعك قم بتفعيل هذا الاختيار');

define($constpref.'_BIGUMBRELLA','anti-XSS (BigUmbrella)الحماية من الهجوم من نوع');
define($constpref.'_BIGUMBRELLADSC','هذا النوع يقوم المهاجم بارسال محتوى من خلالة يحاول سرقة ارقام حسابات وايميلات واي بيانات حساسة من موقع الضحية. الحارس لايوفر حماية كاملة لهذا النوع  لاختلاف انواع الهجوم ');

define($constpref.'_SPAMURI4U','مانع السبام للاعضاء');
define($constpref.'_SPAMURI4UDSC','اي موضوع او تعليق من قبل الاعضاء يحتوي هذا العدد من الروابط سيعتبر سبام وضعك صفر يعني تعطيل الاختيار');
define($constpref.'_SPAMURI4G','مانع السبام للزوار');
define($constpref.'_SPAMURI4GDSC','اي موضوع او تعليق يحتوي هذا العدد من الروابط سيعتبر سبام وضع صفر يعني تعطيلك للاختيار');

}
