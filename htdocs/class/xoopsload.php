<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Xoops Autoload class
 *
 * @category  XoopsLoad
 * @package   Xoops\Core
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
 */
class XoopsLoad
{
    /**
     * holds classes name and classes paths
     *
     * @var array
     */
    protected static $map = array();

    /**
     * Allow modules/preloads/etc to add their own maps
     * Use XoopsLoad::addMap(array('classname', 'path/to/class');
     *
     * @param array $map class map array
     *
     * @return array
     */
    public static function addMap(array $map)
    {
        XoopsLoad::$map = array_merge(XoopsLoad::$map, $map);

        return XoopsLoad::$map;
    }

    /**
     * getMap - return class map
     *
     * @return array
     */
    public static function getMap()
    {
        return XoopsLoad::$map;
    }

    /**
     * load - load file based on type
     *
     * @param string $name class name
     * @param string $type type core, framework, class, module
     *
     * @return bool
     */
    public static function load($name, $type = "core")
    {
        static $loaded;
        static $deprecated;

        if (!isset($deprecated)) {
            $deprecated = array(
                'uploader' => 'xoopsmediauploader', 'utility' => 'xoopsutility', 'captcha' => 'xoopscaptcha',
                'cache'    => 'xoopscache', 'file' => 'xoopsfile', // 'model' => 'xoopsmodelfactory',
                'calendar' => 'xoopscalendar', 'userutility' => 'xoopsuserutility',
            );
        }

        $lname = strtolower($name);
        if (in_array($type, array('core','class')) && array_key_exists($lname, $deprecated)) {
            trigger_error(
                "xoops_load('{$lname}') is deprecated, use xoops_load('{$deprecated[$lname]}')",
                E_USER_WARNING
            );
            $lname = $deprecated[$lname];
        }

        $type = empty($type) ? 'core' : $type;
        if (isset($loaded[$type][$lname])) {
            return $loaded[$type][$lname];
        }

        if (class_exists($lname, false)) {
            $loaded[$type][$lname] = true;

            return true;
        }
        switch ($type) {
            case 'framework':
                $isloaded = self::loadFramework($lname);
                break;
            case 'class':
            case 'core':
                $type = 'core';
                if ($isloaded = self::loadClass($name)) {
                    break;
                }
                $isloaded = self::loadCore($lname);
                break;
            default:
                $isloaded = self::loadModule($lname, $type);
                break;
        }
        $loaded[$type][$lname] = $isloaded;

        return $loaded[$type][$lname];
    }

    /**
     * Load core class
     *
     * @param string $name class name
     *
     * @return bool|string
     */
    private static function loadCore($name)
    {
        $map = XoopsLoad::addMap(XoopsLoad::loadCoreConfig());
        if (isset($map[$name])) {
            //attempt loading from map
            require $map[$name];
            if (class_exists($name) && method_exists($name, '__autoload')) {
                call_user_func(array($name, '__autoload'));
            }

            return true;
        } elseif (self::fileExists($file = XOOPS_ROOT_PATH . '/class/' . $name . '.php')) {
            //attempt loading from file
            include_once $file;
            $class = 'Xoops' . ucfirst($name);
            if (class_exists($class)) {
                return $class;
            } else {
                trigger_error(
                    'Class ' . $name . ' not found in file ' . __FILE__ . 'at line ' . __LINE__,
                    E_USER_WARNING
                );
            }
        }

        return false;
    }

    /**
     * Load Framework class
     *
     * @param string $name framework class name
     *
     * @return false|string
     */
    private static function loadFramework($name)
    {
        if (!self::fileExists($file = XOOPS_ROOT_PATH . '/Frameworks/' . $name . '/xoops' . $name . '.php')) {
            /*
            trigger_error(
                'File ' . str_replace(XOOPS_ROOT_PATH, '', $file)
                . ' not found in file ' . __FILE__ . ' at line ' . __LINE__,
                E_USER_WARNING
            );
            */
            return false;
        }
        include $file;
        $class = 'Xoops' . ucfirst($name);
        if (class_exists($class, false)) {
            return $class;
        }

        return false;
    }

    /**
     * Load module class
     *
     * @param string      $name    class name
     * @param string|null $dirname module dirname
     *
     * @return bool
     */
    private static function loadModule($name, $dirname = null)
    {
        if (empty($dirname)) {
            return false;
        }
        if (self::fileExists($file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/class/' . $name . '.php')) {
            include $file;
            if (class_exists(ucfirst($dirname) . ucfirst($name))) {
                return true;
            }
        }

        return false;
    }

    /**
     * XoopsLoad::loadCoreConfig()
     *
     * @static
     * @return array
     */
    public static function loadCoreConfig()
    {
        return array(
            'bloggerapi' => XOOPS_ROOT_PATH . '/class/xml/rpc/bloggerapi.php',
            'criteria' => XOOPS_ROOT_PATH . '/class/criteria.php',
            'criteriacompo' => XOOPS_ROOT_PATH . '/class/criteria.php',
            'criteriaelement' => XOOPS_ROOT_PATH . '/class/criteria.php',
            'formdhtmltextarea' => XOOPS_ROOT_PATH . '/class/xoopseditor/dhtmltextarea/dhtmltextarea.php',
            'formtextarea' => XOOPS_ROOT_PATH . '/class/xoopseditor/textarea/textarea.php',
            'metaweblogapi' => XOOPS_ROOT_PATH . '/class/xml/rpc/metaweblogapi.php',
            'movabletypeapi' => XOOPS_ROOT_PATH . '/class/xml/rpc/movabletypeapi.php',
            'mytextsanitizer' => XOOPS_ROOT_PATH . '/class/module.textsanitizer.php',
            'mytextsanitizerextension' => XOOPS_ROOT_PATH . '/class/module.textsanitizer.php',
            //'phpmailer' => XOOPS_ROOT_PATH . '/class/mail/phpmailer/class.phpmailer.php',
            'rssauthorhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsscategoryhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsscommentshandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsscopyrighthandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssdescriptionhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssdocshandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssgeneratorhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssguidhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssheighthandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssimagehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssitemhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsslanguagehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsslastbuilddatehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsslinkhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssmanagingeditorhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssnamehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsspubdatehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsssourcehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsstextinputhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsstitlehandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssttlhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rssurlhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsswebmasterhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'rsswidthhandler' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'saxparser' => XOOPS_ROOT_PATH . '/class/xml/saxparser.php',
            //'smarty' => XOOPS_PATH . '/smarty/Smarty.class.php',
            'snoopy' => XOOPS_ROOT_PATH . '/class/snoopy.php',
            'sqlutility' => XOOPS_ROOT_PATH . '/class/database/sqlutility.php',
            'tar' => XOOPS_ROOT_PATH . '/class/class.tar.php',
            'xmltaghandler' => XOOPS_ROOT_PATH . '/class/xml/xmltaghandler.php',
            'xoopsadminthemefactory' => XOOPS_ROOT_PATH . '/class/theme.php',
            'xoopsapi' => XOOPS_ROOT_PATH . '/class/xml/rpc/xoopsapi.php',
            //'xoopsauth' => XOOPS_ROOT_PATH . '/class/auth/auth.php',
            //'xoopsauthfactory' => XOOPS_ROOT_PATH . '/class/auth/authfactory.php',
            //'xoopsauthads' => XOOPS_ROOT_PATH . '/class/auth/auth_ads.php',
            //'xoopsauthldap' => XOOPS_ROOT_PATH . '/class/auth/auth_ldap.php',
            //'xoopsauthprovisionning' => XOOPS_ROOT_PATH . '/class/auth/auth_provisionning.php',
            //'xoopsauthxoops' => XOOPS_ROOT_PATH . '/class/auth/auth_xoops.php',
            //'xoopsavatar' => XOOPS_ROOT_PATH . '/kernel/avatar.php',
            //'xoopsavatarhandler' => XOOPS_ROOT_PATH . '/kernel/avatar.php',
            //'xoopsavataruserlink' => XOOPS_ROOT_PATH . '/kernel/avataruserlink.php',
            //'xoopsavataruserlinkhandler' => XOOPS_ROOT_PATH . '/kernel/avataruserlink.php',
            'xoopsblock' => XOOPS_ROOT_PATH . '/kernel/block.php',
            'xoopsblockform' => XOOPS_ROOT_PATH . '/class/xoopsform/blockform.php',
            'xoopsblockhandler' => XOOPS_ROOT_PATH . '/kernel/block.php',
            'xoopsblockmodulelink' => XOOPS_ROOT_PATH . '/kernel/blockmodulelink.php',
            'xoopsblockmodulelinkhandler' => XOOPS_ROOT_PATH . '/kernel/blockmodulelink.php',
            'xoopscache' => XOOPS_ROOT_PATH . '/class/cache/xoopscache.php',
            'xoopscacheengine' => XOOPS_ROOT_PATH . '/class/cache/xoopscache.php',
            'xoopscacheapc' => XOOPS_ROOT_PATH . '/class/cache/apc.php',
            'xoopscachefile' => XOOPS_ROOT_PATH . '/class/cache/file.php',
            'xoopscachememcache' => XOOPS_ROOT_PATH . '/class/cache/memcache.php',
            'xoopscachemodel' => XOOPS_ROOT_PATH . '/class/cache/model.php',
            'xoopscachexcache' => XOOPS_ROOT_PATH . '/class/cache/xcache.php',
            'xoopscache' => XOOPS_ROOT_PATH . '/class/cache/xoopscache.php',
            'xoopscachemodelhandler' => XOOPS_ROOT_PATH . '/kernel/cachemodel.php',
            'xoopscachemodelobject' => XOOPS_ROOT_PATH . '/kernel/cachemodel.php',
            //'xoopscalendar' => XOOPS_ROOT_PATH . '/class/calendar/xoopscalendar.php',
            'xoopscaptcha' => XOOPS_ROOT_PATH . '/class/captcha/xoopscaptcha.php',
            'xoopscaptchamethod' => XOOPS_ROOT_PATH . '/class/captcha/xoopscaptchamethod.php',
            //'xoopscomment' => XOOPS_ROOT_PATH . '/kernel/comment.php',
            //'xoopscommenthandler' => XOOPS_ROOT_PATH . '/kernel/comment.php',
            //'xoopscommentrenderer' => XOOPS_ROOT_PATH . '/class/commentrenderer.php',
            //'xoopsconfigcategory' => XOOPS_ROOT_PATH . '/kernel/configcategory.php',
            //'xoopsconfigcategoryhandler' => XOOPS_ROOT_PATH . '/kernel/configcategory.php',
            'xoopsconfighandler' => XOOPS_ROOT_PATH . '/kernel/config.php',
            'xoopsconfigitem' => XOOPS_ROOT_PATH . '/kernel/configitem.php',
            'xoopsconfigitemhandler' => XOOPS_ROOT_PATH . '/kernel/configitem.php',
            'xoopsconfigoption' => XOOPS_ROOT_PATH . '/kernel/configoption.php',
            'xoopsconfigoptionhandler' => XOOPS_ROOT_PATH . '/kernel/configoption.php',
            'xoopsdatabase' => XOOPS_ROOT_PATH . '/class/database/database.php',
            //'xoopsconnection' => XOOPS_ROOT_PATH . '/class/database/connection.php',
            //'xoopsquerybuilder' => XOOPS_ROOT_PATH . '/class/database/querybuilder.php',
            'xoopsdatabasefactory' => XOOPS_ROOT_PATH . '/class/database/databasefactory.php',
            'xoopsdatabasemanager' => XOOPS_ROOT_PATH . '/class/database/manager.php',
            'xoopsdownloader' => XOOPS_ROOT_PATH . '/class/downloader.php',
            'xoopsmysqldatabase' => XOOPS_ROOT_PATH . '/class/database/mysqldatabase.php',
            'xoopsmysqldatabaseproxy' => XOOPS_ROOT_PATH . '/class/database/mysqldatabaseproxy.php',
            'xoopsmysqldatabasesafe' => XOOPS_ROOT_PATH . '/class/database/mysqldatabasesafe.php',
            'xoopsgroup' => XOOPS_ROOT_PATH . '/kernel/group.php',
            'xoopsgrouphandler' => XOOPS_ROOT_PATH . '/kernel/group.php',
            'xoopsgroupperm' => XOOPS_ROOT_PATH . '/kernel/groupperm.php',
            'xoopsgrouppermhandler' => XOOPS_ROOT_PATH . '/kernel/groupperm.php',
            //'xoopsimage' => XOOPS_ROOT_PATH . '/kernel/image.php',
            //'xoopsimagecategory' => XOOPS_ROOT_PATH . '/kernel/imagecategory.php',
            //'xoopsimagecategoryhandler' => XOOPS_ROOT_PATH . '/kernel/imagecategory.php',
            //'xoopsimagehandler' => XOOPS_ROOT_PATH . '/kernel/image.php',
            //'xoopsimageset' => XOOPS_ROOT_PATH . '/kernel/imageset.php',
            //'xoopsimagesethandler' => XOOPS_ROOT_PATH . '/kernel/imageset.php',
            //'xoopsimagesetimg' => XOOPS_ROOT_PATH . '/kernel/imagesetimg.php',
            //'xoopsimagesetimghandler' => XOOPS_ROOT_PATH . '/kernel/imagesetimg.php',
            'xoopslists' => XOOPS_ROOT_PATH . '/class/xoopslists.php',
            //'xoopslocal' => XOOPS_ROOT_PATH . '/include/xoopslocal.php',
            //'xoopslocalabstract' => XOOPS_ROOT_PATH . '/class/xoopslocal.php',
            'xoopslogger' => XOOPS_ROOT_PATH . '/class/logger/xoopslogger.php',
            'xoopseditor' => XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php',
            'xoopseditorhandler' => XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php',
            'xoopsfile' => XOOPS_ROOT_PATH . '/class/file/xoopsfile.php',
            'xoopsfilehandler' => XOOPS_ROOT_PATH . '/class/file/file.php',
            'xoopsfilterinput' => XOOPS_ROOT_PATH . '/class/xoopsfilterinput.php',
            'xoopsfolderhandler' => XOOPS_ROOT_PATH . '/class/file/folder.php',
            'xoopsform' => XOOPS_ROOT_PATH . '/class/xoopsform/form.php',
            'xoopsformbutton' => XOOPS_ROOT_PATH . '/class/xoopsform/formbutton.php',
            'xoopsformbuttontray' => XOOPS_ROOT_PATH . '/class/xoopsform/formbuttontray.php',
            //'xoopsformcalendar' => XOOPS_ROOT_PATH . '/class/xoopsform/formcalendar.php',
            'xoopsformcaptcha' => XOOPS_ROOT_PATH . '/class/xoopsform/formcaptcha.php',
            'xoopsformcheckbox' => XOOPS_ROOT_PATH . '/class/xoopsform/formcheckbox.php',
            'xoopsformcolorpicker' => XOOPS_ROOT_PATH . '/class/xoopsform/formcolorpicker.php',
            'xoopsformcontainer' => XOOPS_ROOT_PATH . '/class/xoopsform/formcontainer.php',
            'xoopsformdatetime' => XOOPS_ROOT_PATH . '/class/xoopsform/formdatetime.php',
            'xoopsformdhtmltextarea' => XOOPS_ROOT_PATH . '/class/xoopsform/formdhtmltextarea.php',
            'xoopsformeditor' => XOOPS_ROOT_PATH . '/class/xoopsform/formeditor.php',
            'xoopsformelement' => XOOPS_ROOT_PATH . '/class/xoopsform/formelement.php',
            'xoopsformelementtray' => XOOPS_ROOT_PATH . '/class/xoopsform/formelementtray.php',
            'xoopsformfile' => XOOPS_ROOT_PATH . '/class/xoopsform/formfile.php',
            'xoopsformhidden' => XOOPS_ROOT_PATH . '/class/xoopsform/formhidden.php',
            'xoopsformhiddentoken' => XOOPS_ROOT_PATH . '/class/xoopsform/formhiddentoken.php',
            'xoopsformlabel' => XOOPS_ROOT_PATH . '/class/xoopsform/formlabel.php',
            'xoopsformloader' => XOOPS_ROOT_PATH . '/class/xoopsformloader.php',
            'xoopsformpassword' => XOOPS_ROOT_PATH . '/class/xoopsform/formpassword.php',
            'xoopsformradio' => XOOPS_ROOT_PATH . '/class/xoopsform/formradio.php',
            'xoopsformradioyn' => XOOPS_ROOT_PATH . '/class/xoopsform/formradioyn.php',
            'xoopsformraw' => XOOPS_ROOT_PATH . '/class/xoopsform/formraw.php',
            'xoopsformselect' => XOOPS_ROOT_PATH . '/class/xoopsform/formselect.php',
            'xoopsformselectcheckgroup' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectcheckgroup.php',
            'xoopsformselectcountry' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectcountry.php',
            'xoopsformselecteditor' => XOOPS_ROOT_PATH . '/class/xoopsform/formselecteditor.php',
            'xoopsformselectgroup' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectgroup.php',
            'xoopsformselectlang' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectlang.php',
            'xoopsformselectlocale' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectlocale.php',
            'xoopsformselectmatchoption' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectmatchoption.php',
            'xoopsformselecttheme' => XOOPS_ROOT_PATH . '/class/xoopsform/formselecttheme.php',
            'xoopsformselecttimezone' => XOOPS_ROOT_PATH . '/class/xoopsform/formselecttimezone.php',
            'xoopsformselectuser' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectuser.php',
            'xoopsformtab' => XOOPS_ROOT_PATH . '/class/xoopsform/formtab.php',
            'xoopsformtabtray' => XOOPS_ROOT_PATH . '/class/xoopsform/formtabtray.php',
            'xoopsformtext' => XOOPS_ROOT_PATH . '/class/xoopsform/formtext.php',
            'xoopsformtextarea' => XOOPS_ROOT_PATH . '/class/xoopsform/formtextarea.php',
            'xoopsformtextdateselect' => XOOPS_ROOT_PATH . '/class/xoopsform/formtextdateselect.php',
            'xoopsformmail' => XOOPS_ROOT_PATH . '/class/xoopsform/formmail.php',
            'xoopsformurl' => XOOPS_ROOT_PATH . '/class/xoopsform/formurl.php',
            'xoopsgroupformcheckbox' => XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php',
            'xoopsgrouppermform' => XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php',
            'xoopsguestuser' => XOOPS_ROOT_PATH . '/kernel/user.php',
            'xoopsmailer' => XOOPS_ROOT_PATH . '/class/xoopsmailer.php',
            'xoopsmediauploader' => XOOPS_ROOT_PATH . '/class/uploader.php',
            'xoopsmemberhandler' => XOOPS_ROOT_PATH . '/kernel/member.php',
            'xoopsmembership' => XOOPS_ROOT_PATH . '/kernel/membership.php',
            'xoopsmembershiphandler' => XOOPS_ROOT_PATH . '/kernel/membership.php',
            //'xoopsmodelfactory' => XOOPS_ROOT_PATH . '/class/model/xoopsmodel.php',
            //'xoopsmoduleadmin' => XOOPS_ROOT_PATH . '/class/moduleadmin.php',
            'xoopsmodule' => XOOPS_ROOT_PATH . '/kernel/module.php',
            'xoopsmodulehandler' => XOOPS_ROOT_PATH . '/kernel/module.php',
            'xoopsmultimailer' => XOOPS_ROOT_PATH . '/class/xoopsmultimailer.php',
            //'xoopsnotification' => XOOPS_ROOT_PATH . '/kernel/notification.php',
            //'xoopsnotificationhandler' => XOOPS_ROOT_PATH . '/kernel/notification.php',
            'xoopsobject' => XOOPS_ROOT_PATH . '/kernel/object.php',
            'xoopsobjecthandler' => XOOPS_ROOT_PATH . '/kernel/object.php',
            'xoopsobjecttree' => XOOPS_ROOT_PATH . '/class/tree.php',
            'xoopsonline' => XOOPS_ROOT_PATH . '/kernel/online.php',
            'xoopsonlinehandler' => XOOPS_ROOT_PATH . '/kernel/online.php',
            'xoopspagenav' => XOOPS_ROOT_PATH . '/class/pagenav.php',
            'xoopspersistableobjecthandler' => XOOPS_ROOT_PATH . '/kernel/object.php',
            'xoopspreload' => XOOPS_ROOT_PATH . '/class/preload.php',
            'xoopspreloaditem' => XOOPS_ROOT_PATH . '/class/preload.php',
            'xoopsprivmessage' => XOOPS_ROOT_PATH . '/kernel/privmessage.php',
            'xoopsprivmessagehandler' => XOOPS_ROOT_PATH . '/kernel/privmessage.php',
            'xoopsranks' => XOOPS_ROOT_PATH . '/kernel/ranks.php',
            'xoopsrankshandler' => XOOPS_ROOT_PATH . '/kernel/ranks.php',
            //'xoopsregistry' => XOOPS_ROOT_PATH . '/class/registry.php',
            'xoopsrequest' => XOOPS_ROOT_PATH . '/class/xoopsrequest.php',
            //'xoopssecurity' => XOOPS_ROOT_PATH . '/class/xoopssecurity.php',
            'xoopssessionhandler' => XOOPS_ROOT_PATH . '/kernel/session.php',
            'xoopssimpleform' => XOOPS_ROOT_PATH . '/class/xoopsform/simpleform.php',
            'xoopstableform' => XOOPS_ROOT_PATH . '/class/xoopsform/tableform.php',
            'xoopstardownloader' => XOOPS_ROOT_PATH . '/class/tardownloader.php',
            'xoopstheme' => XOOPS_ROOT_PATH . '/class/theme.php',
            'xoopsthemeblocksplugin' => XOOPS_ROOT_PATH . '/class/theme_blocks.php',
            'xoopsthemefactory' => XOOPS_ROOT_PATH . '/class/theme.php',
            'xoopsthemeform' => XOOPS_ROOT_PATH . '/class/xoopsform/themeform.php',
            'xoopsthemeplugin' => XOOPS_ROOT_PATH . '/class/theme.php',
            'xoopsthemesetparser' => XOOPS_ROOT_PATH . '/class/xml/themesetparser.php',
            'xoopstpl' => XOOPS_ROOT_PATH . '/class/template.php',
            'xoopstplfile' => XOOPS_ROOT_PATH . '/kernel/tplfile.php',
            'xoopstplfilehandler' => XOOPS_ROOT_PATH . '/kernel/tplfile.php',
            'xoopstplset' => XOOPS_ROOT_PATH . '/kernel/tplset.php',
            'xoopstplsethandler' => XOOPS_ROOT_PATH . '/kernel/tplset.php',
            'xoopsuser' => XOOPS_ROOT_PATH . '/kernel/user.php',
            'xoopsuserhandler' => XOOPS_ROOT_PATH . '/kernel/user.php',
            'xoopsuserutility' => XOOPS_ROOT_PATH . '/class/userutility.php',
            'xoopsutility' => XOOPS_ROOT_PATH . '/class/utility/xoopsutility.php',
            'xoopsxmlrpcapi' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpcapi.php',
            'xoopsxmlrpcarray' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcbase64'=> XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcboolean' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcdatetime' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcdocument' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcdouble' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcfault' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcint' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcparser' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpcparser.php',
            'xoopsxmlrpcrequest' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcresponse' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcstring' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcstruct' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpctag' => XOOPS_ROOT_PATH . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrss2parser' => XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php',
            'xoopszipdownloader' => XOOPS_ROOT_PATH . '/class/zipdownloader.php',
            'zipfile' => XOOPS_ROOT_PATH . '/class/class.zipfile.php',
        );
    }

    /**
     * XoopsLoad::loadConfig()
     *
     * @param string $data array of configs or dirname of module
     *
     * @return array|bool
     */
    public static function loadConfig($data = null)
    {
        $xoops = Xoops::getInstance();
        $configs = array();
        if (is_array($data)) {
            $configs = $data;
        } else {
            if (!empty($data)) {
                $dirname = $data;
            } elseif ($xoops->isModule()) {
                $dirname = $xoops->module->getVar('dirname', 'n');
            } else {
                return false;
            }
            if (self::fileExists($file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/autoload.php')) {
                if (!$configs = include $file) {
                    return false;
                }
            }
        }

        return array_merge(XoopsLoad::loadCoreConfig(), $configs);
    }

    /**
     * loadFile
     *
     * @param string $file file to load
     * @param bool   $once true to use include_once, false for include
     *
     * @return bool
     */
    public static function loadFile($file, $once = true)
    {
        self::securityCheck($file);
        if (self::fileExists($file)) {
            if ($once) {
                include_once $file;
            } else {
                include $file;
            }

            return true;
        }

        return false;
    }

    /**
     * loadClass
     *
     * @param string $class class to load
     *
     * @return bool
     */
    public static function loadClass($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return true;
        }

        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        if (!self::loadFile(XOOPS_PATH . DIRECTORY_SEPARATOR . $file)) {
            return false;
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            return false;
        }

        if (method_exists($class, '__autoload')) {
            call_user_func(array($class, '__autoload'));
        }

        return true;
    }

    /**
     * Use this method instead of XoopsLoad::fileExists for increasing performance
     *
     * @param string $file file name
     *
     * @return mixed
     */
    public static function fileExists($file)
    {
        static $included = array();
        if (!isset($included[$file])) {
            $included[$file] = file_exists($file);
        }

        return $included[$file];
    }

    /**
     * Ensure that filename does not contain exploits
     *
     * @param string $filename file name
     *
     * @return void
     */
    protected static function securityCheck($filename)
    {
        /**
         * Security check
         */
        if (preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $filename)) {
            exit('Security check: Illegal character in filename');
        }
    }

    /**
     * startAutoloader enable the autoloader
     *
     * @param string $path path of the library directory where composer managed
     *                     vendor directory can be found.
     * @return void
     */
    public static function startAutoloader($path)
    {
        static $libPath = null;

        if ($libPath === null) {
            $loaderPath = $path . '/vendor/autoload.php';
            if (self::fileExists($loaderPath)) {
                $libPath = $path;
                include $loaderPath;
            }
            spl_autoload_register(array('XoopsLoad', 'load'));
        }
    }
}
