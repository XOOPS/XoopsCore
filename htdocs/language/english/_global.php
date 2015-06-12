<?php
// $Id$
// _LANGCODE: en
// _CHARSET : UTF-8
// Translator: XOOPS Translation Team
//define('_PLEASEWAIT','Please Wait');
//define('_FETCHING','Loading...');
//define('_TAKINGBACK','Taking you back to where you were....');
//define('_LOGOUT','Logout');
//define('_SUBJECT','Subject');
//define('_MESSAGEICON','Message Icon');
//define('_COMMENTS','Comments');
//define('_POSTANON','Post Anonymously');
//define('_DISABLESMILEY','Disable smiley');
//define('_DISABLEHTML','Disable html');
//define('_PREVIEW','Preview');
//define('_GO','Go!');
//define('_NESTED','Nested');
//define('_NOCOMMENTS','No Comments');
//define('_FLAT','Flat');
//define('_THREADED','Threaded');
//define('_OLDESTFIRST','Oldest First');
//define('_NEWESTFIRST','Newest First');
//define('_MORE','more...');
//define('_MULTIPAGE','To have your article span multiple pages, insert the word <font color=red>[pagebreak]</font> (with brackets) in the article.');
//define('_IFNOTRELOAD','If the page does not automatically reload, please click <a href=\'%s\'>here</a>');
// Error messages issued by XoopsObject::cleanVars()
//define('_XOBJ_ERR_REQUIRED','%s is required');
//define('_XOBJ_ERR_SHORTERTHAN','%s must be shorter than %d characters.');
// %%%%%%    File Name themeuserpost.php     %%%%%
//define('_PROFILE','Profile');
//define('_POSTEDBY','Posted by');
//define('_VISITWEBSITE','Visit Website');
//define('_SENDPMTO','Send Private Message to %s');
//define('_SENDEMAILTO','Send Email to %s');
//define('_ADD','Add');
//define('_REPLY','Reply');
//define('_DATE','Date'); // Posted date
// %%%%%%    File Name admin_functions.php     %%%%%
//define('_MAIN','Main');
//define('_MANUAL','Manual');
//define('_INFO','Info');
//define('_CPHOME','Control Panel Home');
//define('_YOURHOME','Home Page');
// %%%%%%    File Name misc.php (who's-online popup)    %%%%%
//define('_WHOSONLINE','Who\'s Online');
//define('_GUESTS','Guests');
//define('_MEMBERS','Members');
//define('_ONLINEPHRASE','<strong>%s</strong> user(s) are online');
//define('_ONLINEPHRASEX','<strong>%s</strong> user(s) are browsing <strong>%s</strong>');
//define('_CLOSE','Close'); // Close window
// %%%%%%    File Name module.textsanitizer.php     %%%%%
//define('_QUOTEC','Quote:');
// %%%%%%    File Name admin.php     %%%%%
//define('_NOPERM','Sorry, you don\'t have the permission to access this area.');
// %%%%%        Common Phrases        %%%%%
//define('_NO','No');
//define('_YES','Yes');
//define('_EDIT','Edit');
//define('_DELETE','Delete');
//define('_SUBMIT','Submit');
//define('_MODULENOEXIST','Selected module does not exist!');
//define('_ALIGN','Align');
//define('_LEFT','Left');
//define('_CENTER','Center');
//define('_RIGHT','Right');
//define('_FORM_ENTER','Please enter %s');
//define('_MUSTWABLE','File %s must be writable by the server!'); // %s represents file name
// Module info
//define('_PREFERENCES','Preferences');
//define('_VERSION','Version');
//define('_DESCRIPTION','Description');
//define('_AUTHOR','Author');
//define('_CREDITS','Credits');
//define('_LICENCE','Licence');
//define('_ERRORS','Errors');
//define('_NONE','None');
//define('_ON','on');
//define('_READS','reads');
//define('_WELCOMETO','Welcome to %s');
//define('_SEARCH','Search');
//define('_ALL','All');
//define('_TITLE','Title');
//define('_OPTIONS','Options');
//define('_QUOTE','Quote');
//define('_LIST','List');
//define('_LOGIN','User Login');
//define('_USERNAME','Username: ');
//define('_PASSWORD','Password: ');
//define('_SELECT','Select');
//define('_IMAGE','Image');
//define('_SEND','Send');
//define('_CANCEL','Cancel');
//define('_ASCENDING','Ascending order');
//define('_DESCENDING','Descending order');
//define('_BACK','Back');
//define('_NOTITLE','No title');
//define('_NOCACHE','No Cache');
//define('_CLONE','Clone');
// %%%%%    For xoopsform files %%%%%
//define('_STARTSWITH','Starts with');
//define('_ENDSWITH','Ends with');
//define('_MATCHES','Matches');
//define('_CONTAINS','Contains');
//define('_REQUIRED','Required');
// %%%%%%    File Name commentform.php     %%%%%
//define('_REGISTER','Register');
// %%%%%%    File Name xoopscodes.php     %%%%%
//define('_SIZE','SIZE'); // font size
//define('_FONT','FONT'); // font family
//define('_COLOR','COLOR'); // font color
//define('_EXAMPLE','SAMPLE');
//define('_ENTERURL','Enter the URL of the link you want to add:');
//define('_ENTERWEBTITLE','Enter the web site title:');
//define('_ENTERIMGURL','Enter the URL of the image you want to add.');
//define('_ENTERIMGPOS','Now, enter the position of the image.');
//define('_IMGPOSRORL','\'R\' or \'r\' for right, \'L\' or \'l\' for left, or leave it blank.');
//define('_ERRORIMGPOS','ERROR! Enter the position of the image.');
//define('_ENTEREMAIL','Enter the email address you want to add.');
//define('_ENTERCODE','Enter the codes that you want to add.');
//define('_ENTERQUOTE','Enter the text that you want to be quoted.');
//define('_ENTERTEXTBOX','Please input text into the textbox.');
//define('_ALLOWEDCHAR','Allowed max chars length: ');
//define('_CURRCHAR','Current chars length: ');
//define('_PLZCOMPLETE','Please complete the subject and message fields.');
//define('_MESSAGETOOLONG','Your message is too long.');
/**
* xoops smilie
*/
//define('_AM_ADDSMILE',' Add a new smilie');
//define('_AM_SMILECODE','Code');
//define('_AM_SMILEEMOTION','Description');
//define('_AM_DISPLAYF','Display in the form');
// %%%%%        TIME FORMAT SETTINGS   %%%%%
//define('_SECOND','1 second');
//define('_SECONDS','%s seconds');
//define('_MINUTE','1 minute');
//define('_MINUTES','%s minutes');
//define('_HOUR','1 hour');
//define('_HOURS','%s hours');
//define('_DAY','1 day');
//define('_DAYS','%s days');
//define('_WEEK','1 week');
//define('_MONTH','1 month');
//define('_DATESTRING','Y/n/j G:i:s');
//define('_MEDIUMDATESTRING','Y/n/j G:i');
//define('_SHORTDATESTRING','Y/n/j');
/**
* The following characters are recognized in the format string:
* a - 'am' or 'pm'
* A - 'AM' or 'PM'
* d - day of the month, 2 digits with leading zeros; i.e. '01' to '31'
* D - day of the week, textual, 3 letters; i.e. 'Fri'
* F - month, textual, long; i.e. 'January'
* h - hour, 12-hour format; i.e. '01' to '12'
* H - hour, 24-hour format; i.e. '00' to '23'
* g - hour, 12-hour format without leading zeros; i.e. '1' to '12'
* G - hour, 24-hour format without leading zeros; i.e. '0' to '23'
* i - minutes; i.e. '00' to '59'
* j - day of the month without leading zeros; i.e. '1' to '31'
* l (lowercase 'L') - day of the week, textual, long; i.e. 'Friday'
* L - boolean for whether it is a leap year; i.e. '0' or '1'
* m - month; i.e. '01' to '12'
* n - month without leading zeros; i.e. '1' to '12'
* M - month, textual, 3 letters; i.e. 'Jan'
* s - seconds; i.e. '00' to '59'
* S - English ordinal suffix, textual, 2 characters; i.e. 'th','nd'
* t - number of days in the given month; i.e. '28' to '31'
* T - Timezone setting of this machine; i.e. 'MDT'
* U - seconds since the epoch
* w - day of the week, numeric, i.e. '0' (Sunday) to '6' (Saturday)
* Y - year, 4 digits; i.e. '1999'
* y - year, 2 digits; i.e. '99'
* z - day of the year; i.e. '0' to '365'
* Z - timezone offset in seconds (i.e. '-43200' to '43200')
*/
// %%%%%        LANGUAGE SPECIFIC SETTINGS   %%%%%
//define('_CHARSET','UTF-8');
//define('_LANGCODE','en_US');
// change 0 to 1 if this language is a multi-bytes language
//define('XOOPS_USE_MULTIBYTES','0');
/**
* Additions to 2.4.0
**/
//define('_RESET','Reset');
//define('_RE','Re:');

/**
* Additions to 2.6.0
**/
//define('_FORM_VALID_URL','Starting with http or https');
//define('_FORM_VALID_MAIL','Enter a valid email address');
