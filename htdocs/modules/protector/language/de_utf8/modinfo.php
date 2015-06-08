<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_PROTECTOR_LOADED' ) ) {


// Appended by Xoops Language Checker -GIJOE- in 2009-07-06 05:46:54
define('_MI_PROTECTOR_DBTRAPWOSRV','Never checking _SERVER for anti-SQL-Injection');
define('_MI_PROTECTOR_DBTRAPWOSRVDSC','Some servers always enable DB Layer trapping. It causes wrong detections as SQL Injection attack. If you got such errors, turn this option on. You should know this option weakens the security of DB Layer trapping anti-SQL-Injection.');

define('_MI_PROTECTOR_LOADED' , 1 ) ;

// The name of this module
define("_MI_PROTECTOR_NAME","Protector");

// A brief description of this module
define("_MI_PROTECTOR_DESC","Diese Module schutzt Ihre Seite vor verschiedenen Angriffen z.B. DoS , SQL Injection , ....");

// Menu
define("_MI_PROTECTOR_ADMININDEX","Protect Center");
define("_MI_PROTECTOR_ADVISORY","Sicherheitsberatung");
define("_MI_PROTECTOR_PREFIXMANAGER","Prefix Manager");
//define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN','Berechtigungen') ;

// Configs
define('_MI_PROTECTOR_GLOBAL_DISBL','Vorubergehend deaktiviert');
define('_MI_PROTECTOR_GLOBAL_DISBLDSC','Alle Sicherheitsfunktionen sind vorubergehend deaktiviert!<br />Vergessen Sie nicht diese wieder einzuschalten, wenn Sie eine Storung beseitigt haben!');

define('_MI_PROTECTOR_DEFAULT_LANG','Voreingestellte Sprache');
define('_MI_PROTECTOR_DEFAULT_LANGDSC','Geben Sie die Sprache fur die Anzeige von Nachrichten bei der Verarbeitung der common.php an.');

define('_MI_PROTECTOR_RELIABLE_IPS','Sichere IPs');
define('_MI_PROTECTOR_RELIABLE_IPSDSC','Sie konnen IP Adressen mit einem | trennen. ^ setzt den Kopf des String, $ setzt das Ende des Strings.');

define('_MI_PROTECTOR_LOG_LEVEL','Berichtsstufe');
//define('_MI_PROTECTOR_LOG_LEVELDSC','Stufe wie genau der Bericht verfasst wird');

define('_MI_PROTECTOR_BANIP_TIME0','Blockadezeit von gesperrten IPs, zeit in Sekunden');

define('_MI_PROTECTOR_LOGLEVEL0','nichts');
define('_MI_PROTECTOR_LOGLEVEL15','still');
define('_MI_PROTECTOR_LOGLEVEL63','still');
define('_MI_PROTECTOR_LOGLEVEL255','voll');

define('_MI_PROTECTOR_HIJACK_TOPBIT','Geschutzte IP bits fur dieses Session');
define('_MI_PROTECTOR_HIJACK_TOPBITDSC','Anti Session Hi-Jacking:<br />Default 32(bit). (Alle Bits sind geschutzt)<br />Wenn Sie keine statische IP Adresse haben, setzen Sie den IP Bereich mit Nummer der einzelnen Bits.<br />(eg) Wenn sich Ihre IP im Bereich von 192.168.0.0 bis 192.168.0.255 befindet, setzen Sie 24(bit) hier');
define('_MI_PROTECTOR_HIJACK_DENYGP','Gruppen denen das andern der IP innerhalb einer Session untersagt wird.');
define('_MI_PROTECTOR_HIJACK_DENYGPDSC','Anti Session Hi-Jacking:<br />Wahlen sie Gruppen aus, denen es untersagt ist, ihre IP wahrend einer Session zu andern..<br />(Mindestens Administrator-Gruppe wird empfohlen.)');
define('_MI_PROTECTOR_SAN_NULLBYTE','Sanitizing (Sauberung) null-bytes');
define('_MI_PROTECTOR_SAN_NULLBYTEDSC','Das Abschluss-Zeichen "\\0" wird oft in Attacken verwendet.<br />Dieses Null-Byte wird in ein Leerzeichen konvertiert.<br />(Einschalten wird dringendst empfohlen!)');
define('_MI_PROTECTOR_DIE_NULLBYTE','Beenden, wenn Null-Bytes gefunden werden');
define('_MI_PROTECTOR_DIE_NULLBYTEDSC','Das Abschluss-Zeichen "\\0" wird oft in Attacken verwendet.<br />(Dringendst empfohlen)');
define('_MI_PROTECTOR_DIE_BADEXT','Beenden, wenn unzulassgige Dateien hochgeladen werden');
define('_MI_PROTECTOR_DIE_BADEXTDSC','Wenn jemand versucht, Dateien mit unzulassigen Endungen wie .php hochzuladen, beendet diese Modul den Zugriff fur XOOPS.<br />Wenn Sie oft Dateien in B-Wiki oder PukiWikiMod einstellen, schalten Sie diese Option aus.');
define('_MI_PROTECTOR_CONTAMI_ACTION','Masnahmen, wenn eine Verunreinigung gefunden wurde:');
define('_MI_PROTECTOR_CONTAMI_ACTIONDS','Wahlen Sie eine Aktion aus, wenn jemand versucht, globale XOOPS-Variablen zu verunreinigen.<br />(Empfohlen wird "Weiser Bildschirm")');
define('_MI_PROTECTOR_ISOCOM_ACTION','Masnahmen, wenn eine isolierte Einkommentierung gefunden wurde:');
define('_MI_PROTECTOR_ISOCOM_ACTIONDSC','Anti SQL Injection:<br />Wahlen Sie eine Massnahme aus, die ergriffen wird, wenn ein  isoliertes "/*" gefunden wird.<br />"Sanitizing (Sauberung)" bedeutet, ein zusatzliches  "*/" anzuhangen.<br />(Empfohlen wird "Sanitizing - Sauberung)" )');
define('_MI_PROTECTOR_UNION_ACTION','Massnahme wenn ein UNION gefunden wurde.');
define('_MI_PROTECTOR_UNION_ACTIONDSC','Anti SQL Injection:<br />Wahlen sie eine Massnahme, wenn ein SQL-Befehl wie UNION gefunden wurde.<br />"Sanitizing (Sauberung)" bedeutet die Anderung von "union" nach "uni-on".<br />(Empfohlen wird Sanitizing - Sauberung)');
define('_MI_PROTECTOR_ID_INTVAL','Erzwinge intval fur Variablen wie IDs');
define('_MI_PROTECTOR_ID_INTVALDSC','Alle Anfragen mit Namen "*id" Werden als Integer behandelt.<br />Diese Option beschutzt sie vor einigen Arten der XSS-(Cross Site Scripting-) und SQL-Injection-Attacken.<br />Obwohl empfohlen wird, diese Option einzuschalten, kann es in einigen Modulen Probleme damit geben.');
define('_MI_PROTECTOR_FILE_DOTDOT','Behebe zweifelhafte Dateiangaben');
define('_MI_PROTECTOR_FILE_DOTDOTDSC','Eliminiertalle ".." aus Anfragen, die nach Dateien suchen');

define('_MI_PROTECTOR_BF_COUNT','Anti Brute Force');
define('_MI_PROTECTOR_BF_COUNTDSC','Setzt die Anzahl der Loginversuchen von Gasten innerhalb 10 minuten. Wenn die Anzahl von Loginversuchen erreicht ist, wird die IP auf die Liste der schlechten IPs gesetzt.');

define('_MI_PROTECTOR_BWLIMIT_COUNT','Bandbreitenbegrenzung');
define('_MI_PROTECTOR_BWLIMIT_COUNTDSC','Geben Sie die maximalen Zugriffe zur mainfile.php an wahrend der Uberwachungszeit. Dieser Wert sollte 0 sein fur eine normale Umgebung, die uber genugend CPU-Bandbreite verfugen. Die Zahl wenniger als 10 werden ignoriert.');

define('_MI_PROTECTOR_DOS_SKIPMODS','Module die nicht auf DoS/Crawler gepruft werden');
define('_MI_PROTECTOR_DOS_SKIPMODSDSC','setzt die Verzeichnisnamen der Module, getrennt durch ein |. Diese Option ist bei Chatmodulen etc. hilfreich');

define('_MI_PROTECTOR_DOS_EXPIRE','','Zeitlimit fur hohe Serverlast (Sekunden)');
define('_MI_PROTECTOR_DOS_EXPIREDSC','Dieser Wert gibt das Zeitlimit fur rasch wiederholte Reloads der Seite (F5 Attacke) und fur Suchmaschinen mit hoher Last an.');

define('_MI_PROTECTOR_DOS_F5COUNT','Anzahl als schadlich eingestufter Reloads F5');
define('_MI_PROTECTOR_DOS_F5COUNTDSC','verhindert DoS Attacken.<br />Der Wert gibt an, wieviele Reloads (F5) als Attacke eingestuft werden.');
define('_MI_PROTECTOR_DOS_F5ACTION','Masnahmen gegen F5 Attacke');

define('_MI_PROTECTOR_DOS_CRCOUNT','Anzahl als schadlich eingestufter Suchmaschinen-Abfragen');
define('_MI_PROTECTOR_DOS_CRCOUNTDSC','Schutzt vor Server-intensiven Abfragen durch Suchmaschinen.<br />Dieser Wert gibt an, wieviele Zugriffe als Server-intensiv eingestuft werden.');
define('_MI_PROTECTOR_DOS_CRACTION','Masnahmen gegen Server-intensive Suchmaschinen');

define('_MI_PROTECTOR_DOS_CRSAFE','Zugelassene User-Agents');
define('_MI_PROTECTOR_DOS_CRSAFEDSC','Ein regulaeer Perl-Ausdruck fur User-Agents.<br />Wenn der Ausdruck zutrifft, wird die Suchmaschine niemals als Server-intensiv eingestuft.<br />Bsp: (msnbot|Googlebot|Yahoo! Slurp)/i');

define('_MI_PROTECTOR_OPT_NONE','Keine (nur logging)');
define('_MI_PROTECTOR_OPT_SAN','Sanitizing (Sauberung)');
define('_MI_PROTECTOR_OPT_EXIT','Weiser Bildschirm');
define('_MI_PROTECTOR_OPT_BIP','IP sperren');
define('_MI_PROTECTOR_OPT_BIPTIME0','Ban the IP (moratorium)');

define('_MI_PROTECTOR_DOSOPT_NONE','Keine (nur logging)');
define('_MI_PROTECTOR_DOSOPT_SLEEP','schlafen');
define('_MI_PROTECTOR_DOSOPT_EXIT','Weiser Bildschirm');
define('_MI_PROTECTOR_DOSOPT_BIP','IP sperren');
define('_MI_PROTECTOR_DOSOPT_BIPTIME0','Sperre die IP (moratorium)');
define('_MI_PROTECTOR_DOSOPT_HTA','DENY by .htaccess(Experimental)');

define('_MI_PROTECTOR_BIP_EXCEPT','Gruppen, die niemals als "schlechte IP" eingestuft werden.');
define('_MI_PROTECTOR_BIP_EXCEPTDSC','Ein User, der in dieser Gruppe ist, wird niemals eine IP-Sperre erfahren.<br />(Empfohlen wird, die Administartor-Gruppe anzugeben.)');

define('_MI_PROTECTOR_DISABLES','Deaktiviert die Sicherheitsfeatures von Protector in XOOPS');

define('_MI_PROTECTOR_DBLAYERTRAP','Enable DB Layer trapping anti-SQL-Injection');
define('_MI_PROTECTOR_DBLAYERTRAPDSC','Almost SQL Injection attacks will be canceled by this feature. This feature is required a support from databasefactory. You can check it on Security Advisory page.');

define('_MI_PROTECTOR_BIGUMBRELLA','aktiviere anti-XSS (BigUmbrella)');
define('_MI_PROTECTOR_BIGUMBRELLADSC','Dies schutzt vor Angriffenvia XSS vulnerabilities. Schutzt nicht zu 100%');

define('_MI_PROTECTOR_SPAMURI4U','anti-SPAM: Anzahl URLs fur normale Users');
define('_MI_PROTECTOR_SPAMURI4UDSC','Wenn diese Anzahl von URLs in Beitragen von Usern (nicht Admins) gefunden wird, ist der Beitrag als Spam eingestuft. 0 bedeutet dieses Feature ist deaktiviert.');
define('_MI_PROTECTOR_SPAMURI4G','anti-SPAM: Anzahl URLs fur Gaste');
define('_MI_PROTECTOR_SPAMURI4GDSC','Wenn diese Anzahl von URLs in Beitragen von Gasten gefunden wird, ist der Beitrag als Spam eingestuft. 0 bedeutet dieses Feature ist deaktiviert.');
}
