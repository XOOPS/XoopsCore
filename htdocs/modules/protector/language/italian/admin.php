<?php
//Italian translation: Defkon1 - defkon1(at)gmail(dot)com - www.xoopsitalia.org
//Updated by Ianez - Xoops Italia Staff

// mymenu
define('_MD_A_MYMENU_MYTPLSADMIN','');
define('_MD_A_MYMENU_MYBLOCKSADMIN','Permessi');
define('_MD_A_MYMENU_MYPREFERENCES','Preferenze');

// index.php
define("_AM_TH_DATETIME","Data/Ora");
define("_AM_TH_USER","Utente");
define("_AM_TH_IP","IP");
define("_AM_TH_AGENT","Agente");
define("_AM_TH_TYPE","Tipo");
define("_AM_TH_DESCRIPTION","Descrizione");

define("_AM_TH_BADIPS" , 'IP malevoli<br /><br /><span style="font-weight:normal;">Inserire un IP per linea.<br />Se lasciato vuoto, tutti gli IP sono autorizzati</span>' ) ;

define("_AM_TH_GROUP1IPS" , 'IP autorizzati per Gruppo=1<br /><br /><span style="font-weight:normal;">Inserire un IP per linea.<br />192.168. equivale a 192.168.*<br />Se lasciato vuoto, tutti gli IP sono autorizzati</span>' ) ;

define("_AM_LABEL_COMPACTLOG" , "Log compatto" ) ;
define("_AM_BUTTON_COMPACTLOG" , "Compatta!" ) ;
define("_AM_JS_COMPACTLOGCONFIRM" , "I record duplicati (IP,Tipo) verranno rimossi!" ) ;
define("_AM_LABEL_REMOVEALL" , "Rimuovi tutti i record" ) ;
define("_AM_BUTTON_REMOVEALL" , "Rimuovi tutto!" ) ;
define("_AM_JS_REMOVEALLCONFIRM" , "Tutti i log verranno rimossi definitivamente. Sei VERAMENTE sicuro?" ) ;
define("_AM_LABEL_REMOVE" , "Rimuovi i record selezionati:" ) ;
define("_AM_BUTTON_REMOVE" , "Rimuovi!" ) ;
define("_AM_JS_REMOVECONFIRM" , "Sei sicuro di voler rimuovere i record selezionati?" ) ;
define("_AM_MSG_IPFILESUPDATED" , "File degli IP aggiornati" ) ;
define("_AM_MSG_BADIPSCANTOPEN" , "Il file degli IP malevoli non pu&ograve; essere aperto" ) ;
define("_AM_MSG_GROUP1IPSCANTOPEN" , "Il file delle autorizzazioni per il Gruppo=1 non pu&ograve; essere aperto" ) ;
define("_AM_MSG_REMOVED" , "Record rimossi" ) ;
//define("_AM_FMT_CONFIGSNOTWRITABLE" , "Imposta la cartella delle opzioni scrivibile: %s" ) ;

// prefix_manager.php
define("_AM_H3_PREFIXMAN" , "Gestore prefissi" ) ;
define("_AM_MSG_DBUPDATED" , "Database aggiornato correttamente!" ) ;
define("_AM_CONFIRM_DELETE" , "Tutti i dati verranno eliminati. Sei sicuro?" ) ;
define("_AM_TXT_HOWTOCHANGEDB" , "Se vuoi cambiare il prefisso tabelle,<br /> modifica il file %s/mainfile.php manualmente.<br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');" ) ;

// advisory.php
define("_AM_ADV_NOTSECURE","Non sicuro");

define('_AM_ADV_TRUSTPATHPUBLIC','Se &egrave; possibile vedere un\'immagine con la scritta -NG- o il link riporta a una pagina normale, la cartella impostata per la XOOPS_TRUST_PATH non &egrave; collocata propriamente. La miglior posizione per la cartella XOOPS_TRUST_PATH &egrave; al di fuori della Root di Xoops. Se non &egrave; possibile spostarla si pu&ograve;, in alternativa, creare un file .htaccess (order deny,allow deny from all) all\'interno della cartella XOOPS_TRUST_PATH.');
define('_AM_ADV_TRUSTPATHPUBLICLINK','Verificare che i files all\'interno della XOOPS_TRUST_PATH siano privati (dovrebbe restituire un errore 404,403 o 500)');
define("_AM_ADV_REGISTERGLOBALS","Questa impostazione permette una gran variet&agrave; di attacchi basati su iniezione.<br />Se possibile, impostare correttamente il file .htaccess");
define("_AM_ADV_ALLOWURLFOPEN","Questa impostazione permette agli attaccanti di eseguire script arbitrari sul server remoto.<br />Solo gli amministratori del server possono modificare questa opzione.<br />Se sei tu l'amministratore, modifica il file php.ini o il file httpd.conf.<br /><b>Esempio di file httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />In alternativa, richiedilo agli amministratori del tuo server.");
define("_AM_ADV_USETRANSSID","Il tuo ID di sessione verr&agrave; mostrato nei tag ancora, ecc...<br />Per prevenire il dirottamento di sessione (session hijacking), aggiungi la seguente linea al tuo file .htaccess nella root principale.<br /><b>php_flag session.use_trans_sid off</b>");
define("_AM_ADV_DBPREFIX","Questa impostazione permette attacchi basati su iniezione SQL.<br />Non dimenticare di impostare 'Forza sterilizzazione *' nelle preferenze di questo modulo.");
define("_AM_ADV_LINK_TO_PREFIXMAN","Vai a Gestore prefissi");
define("_AM_ADV_MAINUNPATCHED","&Egrave; necessario modificare il file mainfile.php come scritto nel README.");
define('_AM_ADV_DBFACTORYPATCHED','Il file databasefactory &egrave; pronto per il DBLayer Trapping anti-SQL-Injection. ');
define('_AM_ADV_DBFACTORYUNPATCHED','Il file databasefactory non &egrave; pronto per il DBLayer Trapping anti-SQL-Injection. Sono necessarie alcune patch. ');

define("_AM_ADV_SUBTITLECHECK","Controlla se Protector funziona correttamente");
define("_AM_ADV_CHECKCONTAMI","Contaminazioni");
define("_AM_ADV_CHECKISOCOM","Commenti isolati");
