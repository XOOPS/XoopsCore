<?php

// mymenu

// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:53
define('_AM_ADV_DBFACTORYPATCHED', 'Ihre Databasefactory ist nicht bereit für DBLayer Trapping anti-SQL-Injection');
define('_AM_ADV_DBFACTORYUNPATCHED', 'Ihre Databasefactory ist nicht bereit für DBLayer Trapping anti-SQL-Injection. Einige Patches sind erforderlich.');

// Appended by Xoops Language Checker -GIJOE- in 2008-12-03 11:47:20

define('_AM_ADV_TRUSTPATHPUBLICLINK', 'Check php files inside TRUST_PATH are private (it must be 404,403 or 500 error');

define('_AM_ADV_TRUSTPATHPUBLIC', 'Wenn Sie eine Grafik aufrufen bzw. sehen können oder der Link zeigt Ihnen eine normale Website an, scheint der sog. trust_path nicht korrekt plaziert zu sein, z.B. innerhalb des Rootverzeichnisses! Der trust_path muss auserhalb liegen, andernfalls ist ihr System nicht ausreichend geschützt! In manchen Fällen kann kein trust_path außerhalb des Rootverzeichnisses gesetzt werden, in dem Fall können Sie eine .htaccess Datei mit dem Inhalt DENY FROM ALL erstellen und in das Verzeichnis kopieren. Dies ist zumindest eine Ersatzlösung, wenn auch abweichend.');
define('_AM_ADV_TRUSTPATHPUBLICLINK', 'Überprüfen Sie PHP Dateien innerhalb des trust_Path, dass sie als Privat gesetzt sind (sie müssen eine 404,403 oder 500 Fehlermeldung erhalten, und wenn Fehlerseiten seitens des Providers nicht erlaubt sind, dann eine weisse Seite.');

// Appended by Xoops Language Checker -GIJOE- in 2007-10-18 05:36:24
define('_AM_LABEL_COMPACTLOG', 'Komprimierter Bericht : ');
define('_AM_BUTTON_COMPACTLOG', 'komprimieren');
define('_AM_JS_COMPACTLOGCONFIRM', 'Doppelt aufgezeichnete IPs und Angriffstypen werden zusammengefasst');
define('_AM_LABEL_REMOVEALL', 'Aufzeichnungen löschen: ');
define('_AM_BUTTON_REMOVEALL', 'Löschen!');
define('_AM_JS_REMOVEALLCONFIRM', 'Sind Sie sicher dass alle Aufzeichungen gelöscht werden sollen?');

// Appended by Xoops Language Checker -GIJOE- in 2007-07-30 05:37:51
//define("_AM_FMT_CONFIGSNOTWRITABLE" , "Der Ordner: %s braucht Schreibberechtigung (777)" ) ;

define('_MD_A_MYMENU_MYTPLSADMIN', '');
define('_MD_A_MYMENU_MYBLOCKSADMIN', 'Erlaubnis');
define('_MD_A_MYMENU_MYPREFERENCES', 'Einstellungen');

// index.php
define('_AM_TH_DATETIME', 'Zeit');
define('_AM_TH_USER', 'Benutzer');
define('_AM_TH_IP', 'IP');
define('_AM_TH_AGENT', 'Client');
define('_AM_TH_TYPE', 'Typ');
define('_AM_TH_DESCRIPTION', 'Beschreibung');

define('_AM_TH_BADIPS', '"Schlechte" IPs');

define('_AM_TH_GROUP1IPS', 'Erlaubte IPs für Gruppe=1<br /><br /><span style="font-weight:normal;">Jede IP in eine Zeile.<br />192.168. bedeutet 192.168.*<br />Leer Bedeutet alle IPs sind erlaubt</span>');

define('_AM_LABEL_REMOVE', 'Markierte Einträge loeschen:');
define('_AM_BUTTON_REMOVE', 'Entfernen!');
define('_AM_JS_REMOVECONFIRM', 'Entfernen OK?');
define('_AM_MSG_IPFILESUPDATED', 'Dateien für IPs wurden aktualisiert');
define('_AM_MSG_BADIPSCANTOPEN', 'Die Datei für schlechte IPs kann nicht geöffnet werden.');
define('_AM_MSG_GROUP1IPSCANTOPEN', 'The file for allowing group=1 cannot be opened');
define('_AM_MSG_REMOVED', 'Einträge wurden entfernt.');

// prefix_manager.php
define('_AM_H3_PREFIXMAN', 'Prefix Manager');
define('_AM_MSG_DBUPDATED', 'Datenbank wurde erfolgreich aktualisiert!');
define('_AM_CONFIRM_DELETE', 'Alle Daten werden gelöscht. OK?');
define('_AM_TXT_HOWTOCHANGEDB', "Wenn Sie den Präfix ändern wollen,<br /> bearbeiten Sie %s/mainfile.php manuell.<br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');");

// advisory.php
define('_AM_ADV_NOTSECURE', 'Nicht sicher');

define('_AM_ADV_REGISTERGLOBALS', 'Diese Einstellung lädt zu verschiedenen Formen der Code Injection ein.<br />Wenn es geht, setzen Sie eine .htaccess-Datei.');
define('_AM_ADV_ALLOWURLFOPEN', 'Diese Einstellung erlaubt Angreifern, willkuerlich Scripts auf entfernten Sytemen auszufuehren.<br />Nur der Administrator des Servers kann diese Option ändern.<br />Wenn Sie der Admin sind, bearbeiten Sie php.ini or httpd.conf entsprechend.<br /><b>Beispiel für httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />Wenn nicht, wenden Sie sich an Ihren Administrator.');
define('_AM_ADV_USETRANSSID', 'Your Session ID will be diplayed in anchor tags etc.<br />For preventing from session hi-jacking, add a line into .htaccess in XOOPS_ROOT_PATH.<br /><b>php_flag session.use_trans_sid off</b>');
define('_AM_ADV_DBPREFIX', "Diese Einstellung lädt zu 'SQL Injections' ein.<br />Vergessen Sie nicht 'Force sanitizing *' in den Voreinstellungen dieses Moduls zu aktivieren.");
define('_AM_ADV_LINK_TO_PREFIXMAN', 'Zum Präfix-Manager');
define('_AM_ADV_MAINUNPATCHED', 'Xoops Protector kann ihre Seite unter bestimmten Umständen schützen, wenn es aus der mainfile.php aufgerufen wird.<br />Sie sollten diese Datei wie im README beschrieben ändern.');

define('_AM_ADV_SUBTITLECHECK', 'Überprüfen, ob Protector funktioniert');
define('_AM_ADV_CHECKCONTAMI', 'Verseuchung');
define('_AM_ADV_CHECKISOCOM', 'Isolierte Kommentare');
