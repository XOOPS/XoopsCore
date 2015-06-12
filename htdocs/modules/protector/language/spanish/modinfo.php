<?php
// Traducción al español por Colossus (19/1/2008), www.zonadepruebas.com

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_PROTECTOR_LOADED' ) ) {



// Appended by Xoops Language Checker -GIJOE- in 2009-07-06 05:46:52
define('_MI_PROTECTOR_DBTRAPWOSRV','Never checking _SERVER for anti-SQL-Injection');
define('_MI_PROTECTOR_DBTRAPWOSRVDSC','Some servers always enable DB Layer trapping. It causes wrong detections as SQL Injection attack. If you got such errors, turn this option on. You should know this option weakens the security of DB Layer trapping anti-SQL-Injection.');

// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:52
define('_MI_PROTECTOR_DBLAYERTRAP','Activar protección contra inyección de SQL por interceptación en la capa de base de datos');
define('_MI_PROTECTOR_DBLAYERTRAPDSC','Casi todos los ataques de inyección SQL serán neutralizados activando esta opción. Es necesario que la base de datos lo soporte. Puede comprobar si es así en el Asesor de Seguridad.');

// Appended by Xoops Language Checker -GIJOE- in 2008-11-21 04:44:30
define('_MI_PROTECTOR_DEFAULT_LANG','Idioma por defecto');
define('_MI_PROTECTOR_DEFAULT_LANGDSC','Especifique el código de idioma para mostrar mensajes antes de procesar common.php');
define('_MI_PROTECTOR_BWLIMIT_COUNT','Limitación de carga');
define('_MI_PROTECTOR_BWLIMIT_COUNTDSC','Especifique el máximo número de accesos a mainfile.php durante el periodo de vigilancia. Este valor debería de ser 0 para entornos normales con suficiente potencia de CPU. Los números menores de 10 serán ignorados.');

// Appended by Xoops Language Checker -GIJOE- in 2007-07-30 16:31:32
define('_MI_PROTECTOR_BANIP_TIME0','Tiempo de suspensión de IP (seg)');
define('_MI_PROTECTOR_OPT_BIPTIME0','Bloquear la IP (moratorium)');
define('_MI_PROTECTOR_DOSOPT_BIPTIME0','Bloquear la IP (moratorium)');

// Appended by Xoops Language Checker -GIJOE- in 2007-03-29 03:36:14
//define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN','Permisos');

define('_MI_PROTECTOR_LOADED' , 1 ) ;

// The name of this module
define("_MI_PROTECTOR_NAME","Protector");

// A brief description of this module
define("_MI_PROTECTOR_DESC","Este módulo proteje su sitio Xoops de varios tipos de ataques, como DoS , Inyecciones de SQL y contaminación de variables.");

// Menu
define("_MI_PROTECTOR_ADMININDEX","Centro de Protección");
define("_MI_PROTECTOR_ADVISORY","Asesor de Seguridad");
define("_MI_PROTECTOR_PREFIXMANAGER","Administrador de Prefijos");

// Configs
define('_MI_PROTECTOR_GLOBAL_DISBL','Deshabilitado temporalmente');
define('_MI_PROTECTOR_GLOBAL_DISBLDSC','Todas las protecciones fueron deshabilitadas temporaralmente.<br />No olvide apagar esta opción luego de resolver el problema.');

define('_MI_PROTECTOR_RELIABLE_IPS','IPs confiables');
define('_MI_PROTECTOR_RELIABLE_IPSDSC','Fijar IPs confiables separadas con | . ^ iguala el inicio de la serie; $ iguala el final de la serie.');

define('_MI_PROTECTOR_LOG_LEVEL','Nivel de registro');
//define('_MI_PROTECTOR_LOG_LEVELDSC','');

define('_MI_PROTECTOR_LOGLEVEL0','Ninguno');
define('_MI_PROTECTOR_LOGLEVEL15','Callado');
define('_MI_PROTECTOR_LOGLEVEL63','callado');
define('_MI_PROTECTOR_LOGLEVEL255','Completo');

define('_MI_PROTECTOR_HIJACK_TOPBIT','Bits de IP protegidos para la sesión');
define('_MI_PROTECTOR_HIJACK_TOPBITDSC','Contra Secuestro de Sesión:<br />Por defecto 32(bit). (Todos los bits son protegidos)<br />Cuando su IP no es estable, fije el rango de IP por número de bits.<br />Por ejemplo, si su IP Puede moverse en el rango de 192.168.0.0 - 192.168.0.255, fije 24 (bits).');
define('_MI_PROTECTOR_HIJACK_DENYGP','Grupos cuya IP no puede modificarse durante la sesión.');
define('_MI_PROTECTOR_HIJACK_DENYGPDSC','Contra Secuestro de Sesión:<br />Seleccione grupos cuyo IP no puede modificarse durante la sesión.<br />(Recomiendo encender Administradores.)');
define('_MI_PROTECTOR_SAN_NULLBYTE','Limpiar bytes nulos');
define('_MI_PROTECTOR_SAN_NULLBYTEDSC','El caracter de terminación "\\0" con frecuencia es empleado en ataques maliciosos.<br />Los bytes nulos serán cambiados por un espacio.<br />(Altamente recomendado: Encender)');
define('_MI_PROTECTOR_DIE_NULLBYTE','Salir si se detectan bytes nulos');
define('_MI_PROTECTOR_DIE_NULLBYTEDSC','El caracter de terminación "\\0" con frecuencia es empleado en ataques maliciosos.<br />(Altamente recomendado: Encender)');
define('_MI_PROTECTOR_DIE_BADEXT','Salir si se suben archivos malignos');
define('_MI_PROTECTOR_DIE_BADEXTDSC','Si alguien trata de subir archivos con extensiones prohibidas como .php , este módulo lo saca de su sitio XOOPS.<br />Si con frecuencia agrega archivos php en módulos como B-Wiki o PukiWikiMod, apague esta opción.');
define('_MI_PROTECTOR_CONTAMI_ACTION','Acción al detectar una contaminación.');
define('_MI_PROTECTOR_CONTAMI_ACTIONDS','Seleccione la acción frente a una contaminación de variables globales del sistema en su sitio XOOPS.<br />(Opción recomendada: pantalla en blanco)');
define('_MI_PROTECTOR_ISOCOM_ACTION','Acción al detectar un comentario aislado');
define('_MI_PROTECTOR_ISOCOM_ACTIONDSC','Contra Inyección de SQL:<br />Seleccione la acción cuando se detecte una "/*" aislada.<br />"Limpieza" significa agregar otra "*/" al final.<br />(Opción recomendada: Limpieza)');
define('_MI_PROTECTOR_UNION_ACTION','Acción al detectar una UNION');
define('_MI_PROTECTOR_UNION_ACTIONDSC','Contra Inyección de SQL:<br />Seleccione la acción al detectar alguna sintaxis como UNION de SQL.<br />"Limpieza" significa cambiar "union" a "uni-on".<br />(Opción recomendada: Limpieza)');
define('_MI_PROTECTOR_ID_INTVAL','Forzar intervalo a variables como id');
define('_MI_PROTECTOR_ID_INTVALDSC','Todas las peticiones llamadas "*id" serán tratadas como número entero.<br />Esta opción le protege contra algunos ataques XSS e Inyecciones de SQL.<br />Aunque recomiendo activar esta opción, puede causar problemas con algunos módulos.');
define('_MI_PROTECTOR_FILE_DOTDOT','Protección contra Travesías de Directorio');
define('_MI_PROTECTOR_FILE_DOTDOTDSC','Elimina ".." de todas las peticiones que parezcan Travesía de Directorio.');

define('_MI_PROTECTOR_BF_COUNT','Contra Fuerza Bruta');
define('_MI_PROTECTOR_BF_COUNTDSC','Fija la cantidad de veces que un anónimo intenta darse de alta en 10 minutos. Si alguien no puede darse de alta en esta cantidad de ocasiones, su IP será bloqueada.');

define('_MI_PROTECTOR_DOS_SKIPMODS','Módulos exentos de revisión DoS/Crawler');
define('_MI_PROTECTOR_DOS_SKIPMODSDSC','Fija los dirnames de los módulos separados con |. Esta opción es útil con módulos de chat, etc.');

define('_MI_PROTECTOR_DOS_EXPIRE','Tiempo de vigilancia para cargas frecuentes (segundos)');
define('_MI_PROTECTOR_DOS_EXPIREDSC','Este valor especifica el tiempo de vigilancia para cargas frecuentes (Ataque F5) y crawlers de subidas frecuentes.');

define('_MI_PROTECTOR_DOS_F5COUNT','Conteo límite para Ataque F5');
define('_MI_PROTECTOR_DOS_F5COUNTDSC','Prevención de ataques DoS.<br />Este valor especifica el conteo de recargas para ser considerado como un ataque malicioso.');
define('_MI_PROTECTOR_DOS_F5ACTION','Acción contra Ataque F5');

define('_MI_PROTECTOR_DOS_CRCOUNT','Conteo límite para Crawlers');
define('_MI_PROTECTOR_DOS_CRCOUNTDSC','Prevención contra crawlers de carga frecuente.<br />Este valor especifica el conteo de accesos para ser considerados como un crawler malicioso.');
define('_MI_PROTECTOR_DOS_CRACTION','Acción contra Crawlers de carga frecuente');

define('_MI_PROTECTOR_DOS_CRSAFE','Agente-Usuario bienvenido');
define('_MI_PROTECTOR_DOS_CRSAFEDSC','Un patrón de perl regex para Agente-Usuario.<br />Si concuerda, el crawler nunca es considerado como de carga frecuente.<br />Por ejemplo: /(msnbot|Googlebot|Yahoo! Slurp)/i');

define('_MI_PROTECTOR_OPT_NONE','Ninguna (sólo registro)');
define('_MI_PROTECTOR_OPT_SAN','Limpieza');
define('_MI_PROTECTOR_OPT_EXIT','Pantalla en Blanco');
define('_MI_PROTECTOR_OPT_BIP','Bloquear IP');

define('_MI_PROTECTOR_DOSOPT_NONE','Ninguna (sólo registro)');
define('_MI_PROTECTOR_DOSOPT_SLEEP','Dormir');
define('_MI_PROTECTOR_DOSOPT_EXIT','Pantalla en Blanco');
define('_MI_PROTECTOR_DOSOPT_BIP','Bloquear IP');
define('_MI_PROTECTOR_DOSOPT_HTA','NEGAR por .htaccess (experimental)');

define('_MI_PROTECTOR_BIP_EXCEPT','Grupos nunca registrados como IP Malicioso');
define('_MI_PROTECTOR_BIP_EXCEPTDSC','Un usuario que pertenece al grupo especificado aquí jamás será bloqueado.<br />(Recomiendo activar al Administrador.)');

define('_MI_PROTECTOR_DISABLES','Deshabilita características peligrosas de XOOPS');

define('_MI_PROTECTOR_BIGUMBRELLA','Habilitar anti-XSS (BigUmbrella)');
define('_MI_PROTECTOR_BIGUMBRELLADSC','Esto protege de casi cualquier ataque vía vulnerabilidades XSS. Pero no al 100%');

define('_MI_PROTECTOR_SPAMURI4U','Contra SPAM: URLs para usuarios normales');
define('_MI_PROTECTOR_SPAMURI4UDSC','Si esta cantidad de URLs es hallada en datos ENVIADOS por usuarios diferentes al Administrador, el ENVÍO es considerado como SPAM. Cero (0) significa deshabilitar esta característica.');
define('_MI_PROTECTOR_SPAMURI4G','Contra SPAM: URLs para anónimos');
define('_MI_PROTECTOR_SPAMURI4GDSC','Si esta cantidad de URLs es hallada en datos ENVIADOS por anónimos, el ENVÍO es considerado como SPAM. Cero (0) significa deshabilitar esta característica.');

}
