<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( $constpref.'_LOADED' ) ) {






// Appended by Xoops Language Checker -GIJOE- in 2009-07-06 05:46:53
define($constpref.'_DBTRAPWOSRV','Never checking _SERVER for anti-SQL-Injection');
define($constpref.'_DBTRAPWOSRVDSC','Some servers always enable DB Layer trapping. It causes wrong detections as SQL Injection attack. If you got such errors, turn this option on. You should know this option weakens the security of DB Layer trapping anti-SQL-Injection.');

// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:53
define($constpref.'_DBLAYERTRAP','Enable DB Layer trapping anti-SQL-Injection');
define($constpref.'_DBLAYERTRAPDSC','Almost SQL Injection attacks will be canceled by this feature. This feature is required a support from databasefactory. You can check it on Security Advisory page.');

// Appended by Xoops Language Checker -GIJOE- in 2008-11-21 04:44:31
define($constpref.'_DEFAULT_LANG','Default language');
define($constpref.'_DEFAULT_LANGDSC','Specify the language set to display messages before processing common.php');
define($constpref.'_BWLIMIT_COUNT','Bandwidth limitation');
define($constpref.'_BWLIMIT_COUNTDSC','Specify the max access to mainfile.php during watching time. This value should be 0 for normal environments which have enough CPU bandwidth. The number fewer than 10 will be ignored.');

// Appended by Xoops Language Checker -GIJOE- in 2007-07-30 16:31:33
define($constpref.'_BANIP_TIME0','Banned IP suspension time (sec)');
define($constpref.'_OPT_BIPTIME0','Ban the IP (moratorium)');
define($constpref.'_DOSOPT_BIPTIME0','Ban the IP (moratorium)');

// Appended by Xoops Language Checker -GIJOE- in 2007-04-11 05:08:26
define($constpref.'_ADMENU_MYBLOCKSADMIN','Permissions');

define( $constpref.'_LOADED' , 1 ) ;

// The name of this module
define($constpref."_NAME","Стор");

// A brief description of this module
define($constpref."_DESC","Этот модуль защищает ваш сайт на базе XOOPS от различного вида атак, таких как: DoS, SQL Injection и порчи переменных.");

// Menu
define($constpref."_ADMININDEX","Главная");
define($constpref."_ADVISORY","Подсказки");
define($constpref."_PREFIXMANAGER","Управление префиксом БД");

// Configs
define($constpref.'_GLOBAL_DISBL','Временно выключен');
define($constpref.'_GLOBAL_DISBLDSC','Все системы защиты временно отключены.<br />Не забудьте включить их после разрешения ваших проблем с безопасностью');

define($constpref.'_RELIABLE_IPS','Доверенные адреса');
define($constpref.'_RELIABLE_IPSDSC','Установите адреса при заходе для которых проверки безопасности не проводятся. Разделяйте каждый адрес знаком "|". "^" соответствует началу строки, "$" соответствует концу строки.');

define($constpref.'_LOG_LEVEL','Журнал событий');
define($constpref.'_LOG_LEVELDSC','');

define($constpref.'_LOGLEVEL0','Журнал отключен');
define($constpref.'_LOGLEVEL15','Минимум событий');
define($constpref.'_LOGLEVEL63','Минимум событий');
define($constpref.'_LOGLEVEL255','Все события');

define($constpref.'_HIJACK_TOPBIT','Защищенные биты IP для сеанса');
define($constpref.'_HIJACK_TOPBITDSC','Анти-Налет Сеанса:<br />Значение по умолчанию 32 (бит).
 (Все биты защищены)<br />Когда ваш IP не устойчив, установите диапазон IP числом битов.<br />(пример) Если ваш IP может двигаться в диапазон 192.168.0.0-192.168.0.255, установите 24 (бит) здесь');
define($constpref.'_HIJACK_DENYGP','Группы для которых изменение адреса в рамках одной сессии запрещено');
define($constpref.'_HIJACK_DENYGPDSC','Борется с подстановкой сессий:<br />
    Выберите группы для которых адрес в пределах одной сессии постоянен.<br />
    (Рекомендуется всегда включать в список групп группу Администраторов сайта.)');
define($constpref.'_SAN_NULLBYTE','Вычищать символ с нулевым кодом');
define($constpref.'_SAN_NULLBYTEDSC','Заверщающий символ "\\0" часто используется в различных видах атак.<br />
    Этот символ будет заменен на пробел.<br />(рекомендуется всегда включать данную настройку)');
define($constpref.'_DIE_NULLBYTE','Вычищать символ с нулевым кодом');
define($constpref.'_DIE_NULLBYTEDSC','Заверщающий символ "\0" часто используется в различных видах атак.<br />(рекомендуется всегда включать данную настройку)');
define($constpref.'_DIE_BADEXT','Прервать выполнение при загрузке опасного файла');
define($constpref.'_DIE_BADEXTDSC','В случае когда кто-либо попытается загрузить на сайт файл имеющий опасное расширение (например .php) - загрузка страницы будет прервана. Если вам часто приходится загружать такие файлы (например для модулей B-Wiki или PukiWikiMod) - отключите данный параметр.');
define($constpref.'_CONTAMI_ACTION','Действие при обнаружении "грязных" переменных');
define($constpref.'_CONTAMI_ACTIONDS','Выберите действие выполняемое в случае когда кто-либо пытается передать вашему скрипту "грязные" системные переменные XOOPS. (Рекомендуется: пустой экран)');
define($constpref.'_ISOCOM_ACTION','Действие при обнаружении изолированного комментария');
define($constpref.'_ISOCOM_ACTIONDSC','Выберите действие выполняемое при обнаружении строки "/*" без экранировния.<br />"Очистка" подразумевает добавление экранирующих символов "*/".<br />(Рекомендуется: Очистить)');
define($constpref.'_UNION_ACTION','Действие при обнаружении ключевого слова UNION');
define($constpref.'_UNION_ACTIONDSC','Выберите действие выполняемое при обнаружении ключевого слова UNION. "Очистка" предполагает заменение всех вхождений данного слова "UNI-ON". (Рекомендуется: Очистить)');
define($constpref.'_ID_INTVAL','Принудительное преобразование целочисленых переменных (например id)');
define($constpref.'_ID_INTVALDSC','Все запросы вида: "*id" будут возвращены как целые числа.<br />Этот параметр защищает вас от некоторых видов XSS и SQL Injections атак.<br />
    Рекомендуется включить этот параметр и отключать только при возникновении проблем в использовании каких-либо модулей.');
define($constpref.'_FILE_DOTDOT','Защита от Directroy Traversals');
define($constpref.'_FILE_DOTDOTDSC','Удаляет все вхождения последовательности ".." из всех запросов выглядящих как Directory Traversals');

define($constpref.'_BF_COUNT','Защита от подбора пароля');
define($constpref.'_BF_COUNTDSC','Установите максимальное количество попыток входа пользователя за 10 минут. В случае если кто-либо попытается залогиниться большее чем указано количество раз - его адрес будет занесен в черный список.');

define($constpref.'_DOS_SKIPMODS','Исключения модулей от DoS/Crawler защиты');
define($constpref.'_DOS_SKIPMODSDSC','Введите имена каталогов разделенные символом "|" для модулей в которых можно отключить DoS/Crawler защиту. Этот параметр в частности широко применим в модулях чата и других модулях для которых частое обращение к стараницам модулей является нормой.');

define($constpref.'_DOS_EXPIRE','Время ожидания для определения высокой нагрузки (сек)');
define($constpref.'_DOS_EXPIREDSC','Данное значение указывает время ожидания до обнуления счетчика запросов страницы ("Атака F5" и Роботы перегружающие сервер)');

define($constpref.'_DOS_F5COUNT','Счетчик для "Атаки F5"');
define($constpref.'_DOS_F5COUNTDSC','Защищает от DoS атак.<br />
    Это значение указывает количество запросов страницы превышение которого за установленое ранее время ожидания распознается как преднамеренная атака.');
define($constpref.'_DOS_F5ACTION','Действие при обнаружении попытки перегрузки сервера');

define($constpref.'_DOS_CRCOUNT','Счетчик для Роботов');
define($constpref.'_DOS_CRCOUNTDSC','Предупреждает высокую загрузку сервера роботами поисковых систем. Указаное значение задает количество запросов превышение которого за установленое ранее время ожидания распознается как посещение "Неправильным" Роботом');
define($constpref.'_DOS_CRACTION','Действие при обнаружении "Плохих" Роботов.');

define($constpref.'_DOS_CRSAFE','Агенты пользователя (User-Agent) не опознаваемые как "Плохие"');
define($constpref.'_DOS_CRSAFEDSC','Регулярное выражение perl для поля Агента Пользователя (User-Agent).<br />В случае совпадения агента посетителя с указаным выражением - Робот никогда не распознается как "Плохой".<br />Пример: /(msnbot|Googlebot|Yandex|Yahoo! Slurp|StackRambler)/i');

define($constpref.'_OPT_NONE','Ничего (только запись в журнале)');
define($constpref.'_OPT_SAN','Очистка');
define($constpref.'_OPT_EXIT','Пустой экран');
define($constpref.'_OPT_BIP','Добавить адрес в черный список');

define($constpref.'_DOSOPT_NONE','Ничего (только запись в журнале)');
define($constpref.'_DOSOPT_SLEEP','Заснуть');
define($constpref.'_DOSOPT_EXIT','Пустой экран');
define($constpref.'_DOSOPT_BIP','Добавить адрес в черный список');
define($constpref.'_DOSOPT_HTA','Запретить доступ используя .htaccess (экспериментально)');

define($constpref.'_BIP_EXCEPT','Групы пользователей никогда не попадающие в черный список.');
define($constpref.'_BIP_EXCEPTDSC','Рекомендуется всегда добавлять в этот список группу Администраторов сайта.');

define($constpref.'_DISABLES','Деактивировать потенциально опасные функции XOOPS');

define($constpref.'_BIGUMBRELLA','Включить anti-XSS (BigUmbrella)');
define($constpref.'_BIGUMBRELLADSC','Это помогает защитить Вас от нападений через уязвимость XSS. Гарантия не 100%!!');

define($constpref.'_SPAMURI4U','anti-SPAM: Колличество ссылок для пользователей');
define($constpref.'_SPAMURI4UDSC','Если колличество ссылок  в сообщениях от пользователей (кроме Администраторов), превышает указанное, сообщение определяется как СПАМ.<br /> 0 - отключено.');
define($constpref.'_SPAMURI4G','anti-SPAM: Колличество ссылок для гостей');
define($constpref.'_SPAMURI4GDSC','Если колличество ссылок  в сообщениях от гостей, превышает указанное, сообщение определяется как СПАМ.<br />  0 - отключено.');

}
