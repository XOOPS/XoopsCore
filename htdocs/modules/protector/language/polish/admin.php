<?php

// mymenu

// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:52
define('_AM_ADV_DBFACTORYPATCHED', 'Your databasefactory is ready for DBLayer Trapping anti-SQL-Injection');
define('_AM_ADV_DBFACTORYUNPATCHED', 'Your databasefactory is not ready for DBLayer Trapping anti-SQL-Injection. Some patches are required.');

// Appended by Xoops Language Checker -GIJOE- in 2008-12-03 11:47:19
define('_AM_ADV_TRUSTPATHPUBLIC', 'Jeśli możesz oglądać obrazek -NG- albo link pokazuje normalne stronę, twój XOOPS_TRUST_PATH nie jest umieszczony prawidłowo. Najlepszym miejscem na XOOPS_TRUST_PATH jest poza DocumentRoot. Jeśli nie możesz tego zrobić, musisz umieścić co najmniej .htaccess (DENY FROM ALL) w XOOPS_TRUST_PATH.');
define('_AM_ADV_TRUSTPATHPUBLICLINK', 'Musisz upewnić się, że PHP pliki wewnątrz TRUST_PATH są prywatne (musi to być błąd 404,403 lub 500');

define('_MD_A_MYMENU_MYTPLSADMIN', '');
define('_MD_A_MYMENU_MYBLOCKSADMIN', 'Uprawnienia');
define('_MD_A_MYMENU_MYPREFERENCES', 'Preferencje');

// index.php
define('_AM_TH_DATETIME', 'Data');
define('_AM_TH_USER', 'Użytkownik');
define('_AM_TH_IP', 'IP');
define('_AM_TH_AGENT', 'Przeglądarka');
define('_AM_TH_TYPE', 'Typ');
define('_AM_TH_DESCRIPTION', 'Szczegóły');

define('_AM_TH_BADIPS', 'Zbanowane IP<br /><br /><span style="font-weight:normal;">Wpisz każde IP w osobnej linii.<br />Pozostaw puste aby wyłączyć blokowanie IP.</span>');

define('_AM_TH_GROUP1IPS', 'Dozwolone IP dla grupy=1<br /><br /><span style="font-weight:normal;">Wpisz każde IP w osobnej linii.<br />192.168. oznacza 192.168.*</span>');

define('_AM_LABEL_COMPACTLOG', 'Compact log');
define('_AM_BUTTON_COMPACTLOG', 'Compact it!');
define('_AM_JS_COMPACTLOGCONFIRM', 'Duplicated (IP,Type) records will be removed');
define('_AM_LABEL_REMOVEALL', 'Remove all records');
define('_AM_BUTTON_REMOVEALL', 'Remove all!');
define('_AM_JS_REMOVEALLCONFIRM', 'Wszystkie logi bedą na trwałe usunięte. Jesteś tego pewny?');
define('_AM_LABEL_REMOVE', 'Usuñ zaznaczone wpisy:');
define('_AM_BUTTON_REMOVE', 'Usuñ!');
define('_AM_JS_REMOVECONFIRM', 'Na pewno?');
define('_AM_MSG_IPFILESUPDATED', 'Pliki z adresami IP zostały uaktualnione');
define('_AM_MSG_BADIPSCANTOPEN', 'Plik z zablokowanymi adresami IP nie może zostać odczytany');
define('_AM_MSG_GROUP1IPSCANTOPEN', 'Plik z adresami IP dla grupy=1 nie może zostać odczytany');
define('_AM_MSG_REMOVED', 'Zaznaczone wpisy zostały usunięte');
//define("_AM_FMT_CONFIGSNOTWRITABLE" , "Nadaj prawa zapisu dla katalogu: %s" ) ;

// prefix_manager.php
define('_AM_H3_PREFIXMAN', 'Manager prefixu');
define('_AM_MSG_DBUPDATED', 'Baza danych została uaktualniona!');
define('_AM_CONFIRM_DELETE', 'Wszystkie dane zostaną zrzucone. OK?');
define('_AM_TXT_HOWTOCHANGEDB', "Jeśli chcesz zmienić prefix w bazie,<br /> wyedytuj %s/mainfile.php za pomocą dowolnego edytora.<br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');");

// advisory.php
define('_AM_ADV_NOTSECURE', 'Niebezpieczne');

define('_AM_ADV_REGISTERGLOBALS', 'Takie ustawienie pozwala na wiele ataków typu injections.<br />Jeśli to możliwe umieść plik .htaccess, wyedytuj lub utwórz...');
define('_AM_ADV_ALLOWURLFOPEN', 'To ustawienie pozwala na wykonanie niechcianych skryptów na zdalnych serwerach.<br />tylko administrator serwera może zmienić tę opcję.<br />Jeżeli nim jesteś, wyedytuj php.ini lub httpd.conf.<br /><b>Przykład edycji httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />Jeśli nie jesteś adminem serwera, poproś go o to!.');
define('_AM_ADV_USETRANSSID', 'Twoje ID sesji będzie widoczne w tagach odnośników.<br />Aby zabezpieczyć się przed kradzieżą sesji, dodaj następującą linię w pliku .htaccess w katalogu XOOPS_ROOT_PATH.<br /><b>php_flag session.use_trans_sid off</b>');
define('_AM_ADV_DBPREFIX', "Takie ustawienie pozwala na atak typu 'SQL Injections'.<br />Nie zapomnij uaktywnić w ustawieniach opcji 'Wymuszone czyszczanie *'.");
define('_AM_ADV_LINK_TO_PREFIXMAN', 'Przejdź do managera prefixu');
define('_AM_ADV_MAINUNPATCHED', 'Powinieneś wyedytować plik mainfile.php tak jak napisano w pliku README.');

define('_AM_ADV_SUBTITLECHECK', 'Sprawdź, czy Protector jest skuteczny.');
define('_AM_ADV_CHECKCONTAMI', 'Zanieczyszczenie danych');
define('_AM_ADV_CHECKISOCOM', 'Odseparowanie komentarzy');
