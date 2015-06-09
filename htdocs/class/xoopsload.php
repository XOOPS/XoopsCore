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
 * @copyright 2011-2013 XOOPS Project (http://xoops.org)
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

        $lname = strtolower($name);

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
        $map = XoopsLoad::$map; //addMap(XoopsLoad::loadCoreConfig());
        if (isset($map[$name])) {
            //attempt loading from map
            require $map[$name];
            if (class_exists($name) && method_exists($name, '__autoload')) {
                call_user_func(array($name, '__autoload'));
            }

            return true;
        } elseif (self::fileExists($file = \XoopsBaseConfig::get('root-path') . '/class/' . $name . '.php')) {
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
        if (!self::fileExists($file = \XoopsBaseConfig::get('root-path') . '/Frameworks/' . $name . '/xoops' . $name . '.php')) {
            /*
            trigger_error(
                'File ' . str_replace(\XoopsBaseConfig::get('root-path'), '', $file)
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
        if (self::fileExists($file = \XoopsBaseConfig::get('root-path') . '/modules/' . $dirname . '/class/' . $name . '.php')) {
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
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
        return array(
            'bloggerapi' => $xoops_root_path . '/class/xml/rpc/bloggerapi.php',
            'criteria' => $xoops_root_path . '/class/criteria.php',
            'criteriacompo' => $xoops_root_path . '/class/criteria.php',
            'criteriaelement' => $xoops_root_path . '/class/criteria.php',
            'formdhtmltextarea' => $xoops_root_path . '/class/xoopseditor/dhtmltextarea/dhtmltextarea.php',
            'formtextarea' => $xoops_root_path . '/class/xoopseditor/textarea/textarea.php',
            'metaweblogapi' => $xoops_root_path . '/class/xml/rpc/metaweblogapi.php',
            'movabletypeapi' => $xoops_root_path . '/class/xml/rpc/movabletypeapi.php',
            'mytextsanitizer' => $xoops_root_path . '/class/module.textsanitizer.php',
            'mytextsanitizerextension' => $xoops_root_path . '/class/module.textsanitizer.php',
            //'phpmailer' => $xoops_root_path . '/class/mail/phpmailer/class.phpmailer.php',
            'rssauthorhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsscategoryhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsscommentshandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsscopyrighthandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssdescriptionhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssdocshandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssgeneratorhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssguidhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssheighthandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssimagehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssitemhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsslanguagehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsslastbuilddatehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsslinkhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssmanagingeditorhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssnamehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsspubdatehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsssourcehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsstextinputhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsstitlehandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssttlhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rssurlhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsswebmasterhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'rsswidthhandler' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'saxparser' => $xoops_root_path . '/class/xml/saxparser.php',
            //'smarty' => $xoops_root_path . '/smarty/Smarty.class.php',
            'snoopy' => $xoops_root_path . '/class/snoopy.php',
            'sqlutility' => $xoops_root_path . '/class/database/sqlutility.php',
            'tar' => $xoops_root_path . '/class/class.tar.php',
            'xmltaghandler' => $xoops_root_path . '/class/xml/xmltaghandler.php',
            'xoopsadminthemefactory' => $xoops_root_path . '/class/theme.php',
            'xoopsapi' => $xoops_root_path . '/class/xml/rpc/xoopsapi.php',
            //'xoopsauth' => $xoops_root_path . '/class/auth/auth.php',
            //'xoopsauthfactory' => $xoops_root_path . '/class/auth/authfactory.php',
            //'xoopsauthads' => $xoops_root_path . '/class/auth/auth_ads.php',
            //'xoopsauthldap' => $xoops_root_path . '/class/auth/auth_ldap.php',
            //'xoopsauthprovisionning' => $xoops_root_path . '/class/auth/auth_provisionning.php',
            //'xoopsauthxoops' => $xoops_root_path . '/class/auth/auth_xoops.php',
            //'xoopsavatar' => $xoops_root_path . '/kernel/avatar.php',
            //'xoopsavatarhandler' => $xoops_root_path . '/kernel/avatar.php',
            //'xoopsavataruserlink' => $xoops_root_path . '/kernel/avataruserlink.php',
            //'xoopsavataruserlinkhandler' => $xoops_root_path . '/kernel/avataruserlink.php',
            'xoopsblock' => $xoops_root_path . '/kernel/block.php',
            'xoopsblockform' => $xoops_root_path . '/class/xoopsform/blockform.php',
            'xoopsblockhandler' => $xoops_root_path . '/kernel/block.php',
            'xoopsblockmodulelink' => $xoops_root_path . '/kernel/blockmodulelink.php',
            'xoopsblockmodulelinkhandler' => $xoops_root_path . '/kernel/blockmodulelink.php',
            //'xoopscalendar' => $xoops_root_path . '/class/calendar/xoopscalendar.php',
            'xoopscaptcha' => $xoops_root_path . '/class/captcha/xoopscaptcha.php',
            'xoopscaptchamethod' => $xoops_root_path . '/class/captcha/xoopscaptchamethod.php',
            'xoopscaptchaimage' => $xoops_root_path . '/class/captcha/image.php',
            'xoopscaptcharecaptcha' => $xoops_root_path . '/class/captcha/recaptcha.php',
            'xoopscaptchatext' => $xoops_root_path . '/class/captcha/text.php',
            'xoopscaptchaimagehandler' => $xoops_root_path . '/class/captcha/image/scripts/imageclass.php',
            //'xoopscomment' => $xoops_root_path . '/kernel/comment.php',
            //'xoopscommenthandler' => $xoops_root_path . '/kernel/comment.php',
            //'xoopscommentrenderer' => $xoops_root_path . '/class/commentrenderer.php',
            //'xoopsconfigcategory' => $xoops_root_path . '/kernel/configcategory.php',
            //'xoopsconfigcategoryhandler' => $xoops_root_path . '/kernel/configcategory.php',
            'xoopsconfighandler' => $xoops_root_path . '/kernel/config.php',
            'xoopsconfigitem' => $xoops_root_path . '/kernel/configitem.php',
            'xoopsconfigitemhandler' => $xoops_root_path . '/kernel/configitem.php',
            'xoopsconfigoption' => $xoops_root_path . '/kernel/configoption.php',
            'xoopsconfigoptionhandler' => $xoops_root_path . '/kernel/configoption.php',
            'xoopsdatabase' => $xoops_root_path . '/class/database/database.php',
            //'xoopsconnection' => $xoops_root_path . '/class/database/connection.php',
            //'xoopsquerybuilder' => $xoops_root_path . '/class/database/querybuilder.php',
            'xoopsdatabasefactory' => $xoops_root_path . '/class/database/databasefactory.php',
            'xoopsdatabasemanager' => $xoops_root_path . '/class/database/manager.php',
            'xoopsdownloader' => $xoops_root_path . '/class/downloader.php',
            'xoopsmysqldatabase' => $xoops_root_path . '/class/database/mysqldatabase.php',
            'xoopsmysqldatabaseproxy' => $xoops_root_path . '/class/database/mysqldatabaseproxy.php',
            'xoopsmysqldatabasesafe' => $xoops_root_path . '/class/database/mysqldatabasesafe.php',
            'xoopsgroup' => $xoops_root_path . '/kernel/group.php',
            'xoopsgrouphandler' => $xoops_root_path . '/kernel/group.php',
            'xoopsgroupperm' => $xoops_root_path . '/kernel/groupperm.php',
            'xoopsgrouppermhandler' => $xoops_root_path . '/kernel/groupperm.php',
            //'xoopsimage' => $xoops_root_path . '/kernel/image.php',
            //'xoopsimagecategory' => $xoops_root_path . '/kernel/imagecategory.php',
            //'xoopsimagecategoryhandler' => $xoops_root_path . '/kernel/imagecategory.php',
            //'xoopsimagehandler' => $xoops_root_path . '/kernel/image.php',
            //'xoopsimageset' => $xoops_root_path . '/kernel/imageset.php',
            //'xoopsimagesethandler' => $xoops_root_path . '/kernel/imageset.php',
            //'xoopsimagesetimg' => $xoops_root_path . '/kernel/imagesetimg.php',
            //'xoopsimagesetimghandler' => $xoops_root_path . '/kernel/imagesetimg.php',
            'xoopslists' => $xoops_root_path . '/class/xoopslists.php',
            //'xoopslocal' => $xoops_root_path . '/include/xoopslocal.php',
            //'xoopslocalabstract' => $xoops_root_path . '/class/xoopslocal.php',
            'xoopslogger' => $xoops_root_path . '/class/logger/xoopslogger.php',
            'xoopseditor' => $xoops_root_path . '/class/xoopseditor/xoopseditor.php',
            'xoopseditorhandler' => $xoops_root_path . '/class/xoopseditor/xoopseditor.php',
            'xoopsfile' => $xoops_root_path . '/class/file/xoopsfile.php',
            'xoopsfilehandler' => $xoops_root_path . '/class/file/file.php',
            'xoopsfilterinput' => $xoops_root_path . '/class/xoopsfilterinput.php',
            'xoopsfolderhandler' => $xoops_root_path . '/class/file/folder.php',
            'xoopsform' => $xoops_root_path . '/class/xoopsform/form.php',
            'xoopsformbutton' => $xoops_root_path . '/class/xoopsform/formbutton.php',
            'xoopsformbuttontray' => $xoops_root_path . '/class/xoopsform/formbuttontray.php',
            //'xoopsformcalendar' => $xoops_root_path . '/class/xoopsform/formcalendar.php',
            'xoopsformcaptcha' => $xoops_root_path . '/class/xoopsform/formcaptcha.php',
            'xoopsformcheckbox' => $xoops_root_path . '/class/xoopsform/formcheckbox.php',
            'xoopsformcolorpicker' => $xoops_root_path . '/class/xoopsform/formcolorpicker.php',
            'xoopsformcontainer' => $xoops_root_path . '/class/xoopsform/formcontainer.php',
            'xoopsformdatetime' => $xoops_root_path . '/class/xoopsform/formdatetime.php',
            'xoopsformdhtmltextarea' => $xoops_root_path . '/class/xoopsform/formdhtmltextarea.php',
            'xoopsformeditor' => $xoops_root_path . '/class/xoopsform/formeditor.php',
            'xoopsformelement' => $xoops_root_path . '/class/xoopsform/formelement.php',
            'xoopsformelementtray' => $xoops_root_path . '/class/xoopsform/formelementtray.php',
            'xoopsformfile' => $xoops_root_path . '/class/xoopsform/formfile.php',
            'xoopsformhidden' => $xoops_root_path . '/class/xoopsform/formhidden.php',
            'xoopsformhiddentoken' => $xoops_root_path . '/class/xoopsform/formhiddentoken.php',
            'xoopsformlabel' => $xoops_root_path . '/class/xoopsform/formlabel.php',
            'xoopsformloader' => $xoops_root_path . '/class/xoopsformloader.php',
            'xoopsformpassword' => $xoops_root_path . '/class/xoopsform/formpassword.php',
            'xoopsformradio' => $xoops_root_path . '/class/xoopsform/formradio.php',
            'xoopsformradioyn' => $xoops_root_path . '/class/xoopsform/formradioyn.php',
            'xoopsformraw' => $xoops_root_path . '/class/xoopsform/formraw.php',
            'xoopsformselect' => $xoops_root_path . '/class/xoopsform/formselect.php',
            'xoopsformselectcheckgroup' => $xoops_root_path . '/class/xoopsform/formselectcheckgroup.php',
            'xoopsformselectcountry' => $xoops_root_path . '/class/xoopsform/formselectcountry.php',
            'xoopsformselecteditor' => $xoops_root_path . '/class/xoopsform/formselecteditor.php',
            'xoopsformselectgroup' => $xoops_root_path . '/class/xoopsform/formselectgroup.php',
            'xoopsformselectlang' => $xoops_root_path . '/class/xoopsform/formselectlang.php',
            'xoopsformselectlocale' => $xoops_root_path . '/class/xoopsform/formselectlocale.php',
            'xoopsformselectmatchoption' => $xoops_root_path . '/class/xoopsform/formselectmatchoption.php',
            'xoopsformselecttheme' => $xoops_root_path . '/class/xoopsform/formselecttheme.php',
            'xoopsformselecttimezone' => $xoops_root_path . '/class/xoopsform/formselecttimezone.php',
            'xoopsformselectuser' => $xoops_root_path . '/class/xoopsform/formselectuser.php',
            'xoopsformtab' => $xoops_root_path . '/class/xoopsform/formtab.php',
            'xoopsformtabtray' => $xoops_root_path . '/class/xoopsform/formtabtray.php',
            'xoopsformtext' => $xoops_root_path . '/class/xoopsform/formtext.php',
            'xoopsformtextarea' => $xoops_root_path . '/class/xoopsform/formtextarea.php',
            'xoopsformtextdateselect' => $xoops_root_path . '/class/xoopsform/formtextdateselect.php',
            'xoopsformmail' => $xoops_root_path . '/class/xoopsform/formmail.php',
            'xoopsformurl' => $xoops_root_path . '/class/xoopsform/formurl.php',
            'xoopsgroupformcheckbox' => $xoops_root_path . '/class/xoopsform/grouppermform.php',
            'xoopsgrouppermform' => $xoops_root_path . '/class/xoopsform/grouppermform.php',
            'xoopsguestuser' => $xoops_root_path . '/kernel/user.php',
            'xoopsmailer' => $xoops_root_path . '/class/xoopsmailer.php',
            'xoopsmediauploader' => $xoops_root_path . '/class/uploader.php',
            'xoopsmemberhandler' => $xoops_root_path . '/kernel/member.php',
            'xoopsmembership' => $xoops_root_path . '/kernel/membership.php',
            'xoopsmembershiphandler' => $xoops_root_path . '/kernel/membership.php',
            //'xoopsmodelfactory' => $xoops_root_path . '/class/model/xoopsmodel.php',
            //'xoopsmoduleadmin' => $xoops_root_path . '/class/moduleadmin.php',
            'xoopsmodule' => $xoops_root_path . '/kernel/module.php',
            'xoopsmodulehandler' => $xoops_root_path . '/kernel/module.php',
            'xoopsmultimailer' => $xoops_root_path . '/class/xoopsmultimailer.php',
            //'xoopsnotification' => $xoops_root_path . '/kernel/notification.php',
            //'xoopsnotificationhandler' => $xoops_root_path . '/kernel/notification.php',
            'xoopsobject' => $xoops_root_path . '/kernel/object.php',
            'xoopsobjecthandler' => $xoops_root_path . '/kernel/object.php',
            'xoopsobjecttree' => $xoops_root_path . '/class/tree.php',
            'xoopsonline' => $xoops_root_path . '/kernel/online.php',
            'xoopsonlinehandler' => $xoops_root_path . '/kernel/online.php',
            'xoopspagenav' => $xoops_root_path . '/class/pagenav.php',
            'xoopspersistableobjecthandler' => $xoops_root_path . '/kernel/object.php',
            'xoopspreload' => $xoops_root_path . '/class/preload.php',
            'xoopspreloaditem' => $xoops_root_path . '/class/preload.php',
            'xoopsprivmessage' => $xoops_root_path . '/kernel/privmessage.php',
            'xoopsprivmessagehandler' => $xoops_root_path . '/kernel/privmessage.php',
            'xoopsranks' => $xoops_root_path . '/kernel/ranks.php',
            'xoopsrankshandler' => $xoops_root_path . '/kernel/ranks.php',
            // 'xoopsregistry' => $xoops_root_path . '/class/registry.php',
            'xoopsrequest' => $xoops_root_path . '/class/xoopsrequest.php',
            // 'xoopssecurity' => $xoops_root_path . '/class/xoopssecurity.php',
            // 'xoopssessionhandler' => $xoops_root_path . '/kernel/session.php',
            'xoopssimpleform' => $xoops_root_path . '/class/xoopsform/simpleform.php',
            'xoopstableform' => $xoops_root_path . '/class/xoopsform/tableform.php',
            'xoopstardownloader' => $xoops_root_path . '/class/tardownloader.php',
            'xoopstheme' => $xoops_root_path . '/class/theme.php',
            'xoopsthemeblocksplugin' => $xoops_root_path . '/class/theme_blocks.php',
            'xoopsthemefactory' => $xoops_root_path . '/class/theme.php',
            'xoopsthemeform' => $xoops_root_path . '/class/xoopsform/themeform.php',
            'xoopsthemeplugin' => $xoops_root_path . '/class/theme.php',
            'xoopsthemesetparser' => $xoops_root_path . '/class/xml/themesetparser.php',
            'xoopstpl' => $xoops_root_path . '/class/template.php',
            'xoopstplfile' => $xoops_root_path . '/kernel/tplfile.php',
            'xoopstplfilehandler' => $xoops_root_path . '/kernel/tplfile.php',
            'xoopstplset' => $xoops_root_path . '/kernel/tplset.php',
            'xoopstplsethandler' => $xoops_root_path . '/kernel/tplset.php',
            'xoopsuser' => $xoops_root_path . '/kernel/user.php',
            'xoopsuserhandler' => $xoops_root_path . '/kernel/user.php',
            'xoopsuserutility' => $xoops_root_path . '/class/userutility.php',
            'xoopsutility' => $xoops_root_path . '/class/utility/xoopsutility.php',
            'xoopsxmlrpcapi' => $xoops_root_path . '/class/xml/rpc/xmlrpcapi.php',
            'xoopsxmlrpcarray' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcbase64'=> $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcboolean' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcdatetime' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcdocument' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcdouble' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcfault' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcint' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcparser' => $xoops_root_path . '/class/xml/rpc/xmlrpcparser.php',
            'xoopsxmlrpcrequest' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcresponse' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcstring' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpcstruct' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrpctag' => $xoops_root_path . '/class/xml/rpc/xmlrpctag.php',
            'xoopsxmlrss2parser' => $xoops_root_path . '/class/xml/rss/xmlrss2parser.php',
            'xoopszipdownloader' => $xoops_root_path . '/class/zipdownloader.php',
            'zipfile' => $xoops_root_path . '/class/class.zipfile.php',
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
            if (self::fileExists($file = \XoopsBaseConfig::get('root-path') . '/modules/' . $dirname . '/include/autoload.php')) {
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
        if (!self::loadFile(\XoopsBaseConfig::get('lib-path') . DIRECTORY_SEPARATOR . $file)) {
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
            XoopsLoad::addMap(XoopsLoad::loadCoreConfig());
            spl_autoload_register(array('XoopsLoad', 'load'));
        }
    }
}
