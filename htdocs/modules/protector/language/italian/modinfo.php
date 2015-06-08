<?php
//Italian translation: Defkon1 - defkon1(at)gmail(dot)com - www.xoopsitalia.org
//Updated by Ianez - Xoops Italia Staff

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_PROTECTOR_LOADED' ) ) {

define('_MI_PROTECTOR_LOADED' , 1 ) ;

// The name of this module
define("_MI_PROTECTOR_NAME","Protector");

// A brief description of this module
define("_MI_PROTECTOR_DESC","Questo modulo protegge il tuo sito Xoops da diversi tipi di attacchi, come i Denial Of Service, Iniezione SQL, e contaminazione delle variabili.");

// Menu
define("_MI_PROTECTOR_ADMININDEX","Centro di Protezione");
define("_MI_PROTECTOR_ADVISORY","Pannello Sicurezza");
define("_MI_PROTECTOR_PREFIXMANAGER","Gestore prefissi");
//define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN','Permessi') ;

// Configs
define('_MI_PROTECTOR_GLOBAL_DISBL','Temporaneamente disabilitato');
define('_MI_PROTECTOR_GLOBAL_DISBLDSC','Tutte le protezioni sono disabilitate temporaneamente.<br />Non dimenticare di impostare su No, dopo aver risolto il problema');

define('_MI_PROTECTOR_DEFAULT_LANG','Lingua di default');
define('_MI_PROTECTOR_DEFAULT_LANGDSC','Specificare la lingua in cui visualizzare i messaggi prima di processare common.php');

define('_MI_PROTECTOR_RELIABLE_IPS','IP affidabili');
define('_MI_PROTECTOR_RELIABLE_IPSDSC','Imposta gli IP affidabili separandoli con | . ^ abbina la testa della stringa, $ abbina la coda della stringa.');

define('_MI_PROTECTOR_LOG_LEVEL','Livello del log');
//define('_MI_PROTECTOR_LOG_LEVELDSC','');

define('_MI_PROTECTOR_BANIP_TIME0','Tempo di espulsione degli IP (sec)');

define('_MI_PROTECTOR_LOGLEVEL0','Nessuno');
define('_MI_PROTECTOR_LOGLEVEL15','Silenzioso');
define('_MI_PROTECTOR_LOGLEVEL63','Basso');
define('_MI_PROTECTOR_LOGLEVEL255','Totale');

define('_MI_PROTECTOR_HIJACK_TOPBIT','Bit dell\'IP protetti per questa sessione');
define('_MI_PROTECTOR_HIJACK_TOPBITDSC','Anti dirottamento della Sessione:<br />Default 32(bit). (Tutti i bit protetti)<br />Quando il tuo IP non &egrave; statico, imposta un range di tolleranza sul numero di bit protetti dell\'IP.<br />(es.) Se il tuo IP pu&ograve; muoversi nel range 192.168.0.0-192.168.0.255, impostare una protezione di 24(bit)');
define('_MI_PROTECTOR_HIJACK_DENYGP','Gruppi non autorizzati a cambiare IP durante una sessione');
define('_MI_PROTECTOR_HIJACK_DENYGPDSC','Anti dirottamento sessione:<br />Selezionare i gruppi a cui non &egrave; permesso cambiare IP durante una sessione.<br />(Raccomandato: tutti i gruppi di Amministrazione.)');
define('_MI_PROTECTOR_SAN_NULLBYTE','Sterilizza null-bytes');
define('_MI_PROTECTOR_SAN_NULLBYTEDSC','Il carattere terminale "\\0" &egrave; spesso utilizzato negli attacchi malevolis.<br />Ogni null-byte verr&agrave; sostituito con uno spazio.<br />(Raccomandato: S&igrave;)');
define('_MI_PROTECTOR_DIE_NULLBYTE','Esci se viene identificato un null-bytes');
define('_MI_PROTECTOR_DIE_NULLBYTEDSC','Il carattere terminale "\\0" &egrave; spesso utilizzato negli attacchi malevolis.<br />(Raccomandato: S&igrave;)');
define('_MI_PROTECTOR_DIE_BADEXT','Esci se vengono inviati file malevoli');
define('_MI_PROTECTOR_DIE_BADEXTDSC','Se qualcuno cerca di effettuare l\'upload di file con estensione potenzialmente pericolosa (ad es. .php), il modulo esce da Xoops.<br />Se carichi spesso file php in moduli tipo B-Wiki o PukiWikiMod, disattivare questa funzione.');
define('_MI_PROTECTOR_CONTAMI_ACTION','Azione se rilevata contaminazione');
define('_MI_PROTECTOR_CONTAMI_ACTIONDS','Seleziona l\'azione da intraprendere qualora venga identificata una contaminazione delle variabili globali di sistema in Xoops.<br />(Raccomandato: schermata bianca)');
define('_MI_PROTECTOR_ISOCOM_ACTION','Azione se rilevato commento isolato');
define('_MI_PROTECTOR_ISOCOM_ACTIONDSC','Anti iniezione SQL:<br />Seleziona l\'azione da intraprendere qualora venga identificato un commento isolato ("/*").<br />"Sterilizzazione" significa aggiungere un altro "*/" in coda.<br />(Raccomandato: Sterilizzazione)');
define('_MI_PROTECTOR_UNION_ACTION','Azioen se rilevato UNION');
define('_MI_PROTECTOR_UNION_ACTIONDSC','Anti iniziezione SQL:<br />Seleziona l\'azione da intraprendere qualora venga identificata una sintassi di tipo UNION.<br />"Sterilizza" significa sostituire la parola chiave "union" in "uni-on".<br />(Raccomandato: Sterilizza)');
define('_MI_PROTECTOR_ID_INTVAL','Forza valori interi per le variabili tipo id');
define('_MI_PROTECTOR_ID_INTVALDSC','Tutte le richieste di parametri "*id" verranno trattate come numeri interi.<br />Questa opzione protegge da alcuni tipi di attacchi XSS e a iniezione SQL.<br />(Raccomandato: S&igrave; - Pu&ograve; causare problemi con alcuni oduli)');
define('_MI_PROTECTOR_FILE_DOTDOT','Protezioni da Attraversamento Directory');
define('_MI_PROTECTOR_FILE_DOTDOTDSC','Elimina dai percorsi il ".." da tutte le richieste che assomigliano ad attacchi da Attraversamento Directory');

define('_MI_PROTECTOR_BF_COUNT','Anti Forza Bruta');
define('_MI_PROTECTOR_BF_COUNTDSC','Conteggia il numero di tentativi di login di un utente anonimo in 10 minuti. Se il login fallisce pi&&ugrave; volte di quanto specificato qui, il suo IP viene espulso (Ban).');

define('_MI_PROTECTOR_BWLIMIT_COUNT','Limitazione Banda di accesso');
define('_MI_PROTECTOR_BWLIMIT_COUNTDSC','Specificare l\'ampiezza di banda d\'accesso al mainfile.php (in kilobyte) durante il tempo di visita. Il valore dovrebbe essere 0 per ambienti che hanno una CPU adeguata. I valori inferiori a 10 saranno ignorati.');

define('_MI_PROTECTOR_DOS_SKIPMODS','Moduli esclusi dal controllo DoS/Crawler');
define('_MI_PROTECTOR_DOS_SKIPMODSDSC','Impostare i nomi delle cartelle dei moduli separate da |. Questa opzione &egrave; utile sui moduli chat, ecc...');

define('_MI_PROTECTOR_DOS_EXPIRE','Tempo di controllo per caricamenti frequenti (sec)');
define('_MI_PROTECTOR_DOS_EXPIREDSC','Questo valore specifica il tempo di controllo per i frequenti caricamenti del sito (attacchi da F5) e crawler troppo invasivi.');

define('_MI_PROTECTOR_DOS_F5COUNT','Contatore Attacchi da F5');
define('_MI_PROTECTOR_DOS_F5COUNTDSC','Previene gli attacchi Denial Of Service da F5.<br />Questo valore specifica il numero di caricamenti consecutivi da considerare come attacco malevolo.');
define('_MI_PROTECTOR_DOS_F5ACTION','Azione contro Attacchi da F5');

define('_MI_PROTECTOR_DOS_CRCOUNT','Contatore Crawler');
define('_MI_PROTECTOR_DOS_CRCOUNTDSC','Previene l\'esaurimento delle risorse server da parte di crawlers troppo invasivi.<br />Questo valore specifica il numero di accessi da considerare eccessivi per un crawler.');
define('_MI_PROTECTOR_DOS_CRACTION','Azione contro Crawler troppo invasivi');

define('_MI_PROTECTOR_DOS_CRSAFE','User-Agent benvenuti');
define('_MI_PROTECTOR_DOS_CRSAFEDSC','Un pattern regex per gli User-Agent.<br />Se coincidente, il crawler non verr&agrave; mai considerato troppo invasivo.<br />(es.) /(msnbot|Googlebot|Yahoo! Slurp)/i');

define('_MI_PROTECTOR_OPT_NONE','Nessuna (solo log)');
define('_MI_PROTECTOR_OPT_SAN','Sterilizzazione');
define('_MI_PROTECTOR_OPT_EXIT','Schermata bianca');
define('_MI_PROTECTOR_OPT_BIP','Espulsione IP (Nessun limite)');
define('_MI_PROTECTOR_OPT_BIPTIME0','Espulsione IP (Moratoria)');

define('_MI_PROTECTOR_DOSOPT_NONE','Nessuna (solo log)');
define('_MI_PROTECTOR_DOSOPT_SLEEP','Sospensione');
define('_MI_PROTECTOR_DOSOPT_EXIT','Schermata bianca');
define('_MI_PROTECTOR_DOSOPT_BIP','Espulsione IP (Nessun limite)');
define('_MI_PROTECTOR_DOSOPT_BIPTIME0','Espulsione IP (Moratoria)');
define('_MI_PROTECTOR_DOSOPT_HTA','DENY da .htaccess (Sperimentale)');

define('_MI_PROTECTOR_BIP_EXCEPT','Gruppi da non registrare come IP malevoli');
define('_MI_PROTECTOR_BIP_EXCEPTDSC','Un utente appartenente ai gruppi specificati non verr&agrave; mai espulso.<br />(Raccomandato: tutti i gruppi di Amministrazione)');

define('_MI_PROTECTOR_DISABLES','Disabilita funzionalit&agrave; pericolose di XOOPS');

define('_MI_PROTECTOR_DBLAYERTRAP','Abilitare DB Layer trapping anti-Iniezione SQL');
define('_MI_PROTECTOR_DBLAYERTRAPDSC','Quasi tutti gli attacchi di Iniezione SQL verranno bloccati tramite questa impostazione, che richiede tuttavia il supporto da parte del databasefactory. &Egrave; possibile verificarne lo stato nel \'Pannello Sicurezza\'.');
define('_MI_PROTECTOR_DBTRAPWOSRV','Non verficare sul _SERVER azioni anti-Iniezione SQL');
define('_MI_PROTECTOR_DBTRAPWOSRVDSC','Alcuni server abilitano di default il DB Layer trapping. Il processo pu&ograve; tuttavia erroneamente rilevare alcuni eventi come attacchi di Iniezione SQL. Se si presentano tali errori impostare quest\'opzione su \'on\'.<br /> &Egrave; bene ricordare che questa impostazione diminuisce la sicurezza del DB Layer trapping anti-Iniezione SQL.');

define('_MI_PROTECTOR_BIGUMBRELLA','Abilita sistema anti-XSS (BigUmbrella)');
define('_MI_PROTECTOR_BIGUMBRELLADSC','Questo protegge dalla maggior parte degli attacchi che sfruttano vulnerabilit&agrave; XSS. Ma non funziona al 100%');

define('_MI_PROTECTOR_SPAMURI4U','Anti-SPAM: numero di indirizzi per gli utenti normali');
define('_MI_PROTECTOR_SPAMURI4UDSC','Se in un invio di dati POST da parte di un utente (ad eccezione degli amministratori) vengono rilevati pi&ugrave; indirizzi URL di quanto consentito qui, l\'invio viene considerato SPAM. Impostare 0 per disabilitare questa funzionalit&agrave;.');
define('_MI_PROTECTOR_SPAMURI4G','Anti-SPAM: numero di indirizzi per gli utenti anonimi');
define('_MI_PROTECTOR_SPAMURI4GDSC','Se in un invio di dati POST da parte di un utente anonimi vengono rilevati pi&ugrave; indirizzi URL di quanto consentito qui, l\'invio viene considerato SPAM. Impostare 0 per disabilitare questa funzionalit&agrave;..');

}
