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
 *  TinyMCE 4.x adapter for XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @subpackage      editor
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          Lucio Rota <lucio.rota@gmail.com>
 * @author          Laurent JEN <dugris@frxoops.org>
 * @version         $Id $
 */

class TinyMCE
{
    var $rootpath;
    var $config = array();
    var $setting = array();

  /**
  * PHP 5 Constructor
  *
  * @param    string    $config   The configuration
  **/

    function __construct($config)
     {
        $this->setConfig($config);
        $this->rootpath = $this->config["rootpath"] . "/tinymce/js/tinymce";
    }

  /**
  * Creates one instance of the tinyMCE object
  *
  * @param    array     $config     The configuration
  * @return   object    $instance   The instance of tinyMCE object
  **/

    function &instance( $config )
    {
        static $instance;
        if(!isset($instance)) {
            $instance = new TinyMCE($config);
        }else{
            $instance->setConfig($config);
        }
        return $instance;
    }

    function setConfig( $config )
    {
        foreach ($config as $key => $val) {
            $this->config[$key] = $val;
        }
    }

  /**
  * Initializes the tinyMCE
  * @return   true
  **/

    function init()
    {
        // list of configured options
        $configured = array();
        $this->setting["selector"] = "textarea";
        $this->setting["theme"] = "modern";

        // Load default settings
        if ( ! ($this->setting = @include( $GLOBALS['xoops']->path( "var/configs/tinymce.php" ) ) ) ) {
            $this->setting = include dirname(__FILE__) . "/settings.php";
        }

        // get editor language (from ...)
        if (is_readable(XOOPS_ROOT_PATH . $this->rootpath . '/langs/' . $this->config["language"] . '.js')) {
            $this->setting["language"] = $this->config["language"];
            $configured[] = "language";
        }

        $this->setting["content_css"] = implode( ",", $this->loadCss() );
        $configured[] = "content_css";

        if ( !empty($this->config["theme"]) && is_dir(XOOPS_ROOT_PATH . $this->rootpath . "/themes/" . $this->config["theme"]) ) {
            $this->setting["theme"] = $this->config["theme"];
            $configured[] = "theme";
        }

        if (!empty($this->config["mode"])) {
            $this->setting["mode"] = $this->config["mode"];
            $configured[] = "mode";
        }

        // load all plugins except the plugins in setting["exclude_plugins"]
        $this->setting["plugins"] = implode(",", $this->loadPlugins());
        $configured[] = "plugins";

        if ( $this->setting["theme"] != "simple" ) {
            if (empty($this->config["buttons"])) {
                $this->config["buttons"][] = array(
                    "before"    => "",
                    "add"       => "",
                    );
                $this->config["buttons"][] = array(
                    "before"    => "",
                    "add"       => "",
                    );
                $this->config["buttons"][] = array(
                    "before"    => "",
                    "add"       => "",
                    );
            }
            $i = 0;
            foreach ($this->config["buttons"] as $button) {
                $i++;
                if (isset($button["before"])) {
                    $this->setting["theme_" . $this->setting["theme"] . "_buttons{$i}_add_before"] = $button["before"];
                }
                if (isset($button["add"])) {
                    $this->setting["theme_" . $this->setting["theme"] . "_buttons{$i}_add"] = $button["add"];
                }
                if (isset($button[""])) {
                    $this->setting["theme_" . $this->setting["theme"] . "_buttons{$i}"] = $button[""];
                }
            }
            $configured[] = "buttons";

            if (isset($this->config["toolbar_location"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_toolbar_location"] = $this->config["toolbar_location"];
                $configured[] = "toolbar_location";
            } else {
                $this->setting["theme_" . $this->setting["theme"] . "_toolbar_location"] = "top";
            }

            if (isset($this->config["toolbar_align"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_toolbar_align"] = $this->config["toolbar_align"];
                $configured[] = "toolbar_align";
            } else {
                $this->setting["theme_" . $this->setting["theme"] . "_toolbar_align"] = "left";
            }

            if (isset($this->config["statusbar_location"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_statusbar_location"] = $this->config["statusbar_location"];
                $configured[] = "statusbar_location";
            }

            if (isset($this->config["path_location"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_path_location"] = $this->config["path_location"];
                $configured[] = "path_location";
            }

            if (isset($this->config["resize_horizontal"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_resize_horizontal"] = $this->config["resize_horizontal"];
                $configured[] = "resize_horizontal";
            }

            if (isset($this->config["resizing"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_resizing"] = $this->config["resizing"];
                $configured[] = "resizing";
            }

            if (!empty($this->config["fonts"])) {
                $this->setting["theme_" . $this->setting["theme"] . "_fonts"] = $this->config["fonts"];
                $configured[] = "fonts";
            }

            for ($i=1 ; $i <= 4 ; $i++ ) {
                $buttons = array();
                if ( isset($this->setting["theme_" . $this->setting["theme"] . "_buttons{$i}"]) ) {
                    $checklist = explode(",", $this->setting["theme_" . $this->setting["theme"] . "_buttons{$i}"] );
                    foreach ( $checklist as $plugin ) {
                        if ( strpos( strtolower($plugin), "xoops") != false ) {
                            if ( in_array( $plugin, $this->xoopsPlugins ) ) {
                                $buttons[] = $plugin;
                            }
                        } else {
                            $buttons[] = $plugin;
                        }
                    }
                    $this->setting["theme_" . $this->setting["theme"] . "_buttons{$i}"] = implode(",", $buttons);
                }
            }
        }

        $configured = array_unique($configured);
        foreach ($this->config as $key => $val) {
            if (isset($this->setting[$key]) || in_array($key, $configured)) {
                continue;
            }
            $this->setting[$key] = $val;
        }

        if (!is_dir(XOOPS_ROOT_PATH . $this->rootpath . "/themes/" . $this->setting["theme"] . '/docs/' . $this->setting["language"] . '/')) {
            $this->setting["docs_language"] = "en";
        }

        unset($this->config, $configured);
        return true;
    }

    // load all plugins execpt the plugins in setting["exclude_plugins"]
    function loadPlugins()
    {
        $plugins = array();
        $plugins_list = XoopsLists::getDirListAsArray( XOOPS_ROOT_PATH . $this->rootpath . "/plugins" );
        if (empty($this->setting["plugins"])) {
            $plugins = $plugins_list;
        } else {
            $plugins = array_intersect(explode(",", $this->setting["plugins"]), $plugins_list);
        }
        if (!empty($this->setting["exclude_plugins"])) {
            $plugins = array_diff($plugins, explode(",", $this->setting["exclude_plugins"]));
        }
        if (!empty($this->config["plugins"])) {
            $plugins = array_merge($plugins, $this->config["plugins"]);
        }
        return $plugins;
    }

    // return all xoops plugins
    function get_xoopsPlugins() {
        $xoopsPlugins = array();
        $allplugins = XoopsLists::getDirListAsArray( XOOPS_ROOT_PATH . $this->rootpath . "/plugins" );
        foreach ( $allplugins as $plugin ) {
            if ( strpos( strtolower($plugin), "xoops") != false && file_exists(XOOPS_ROOT_PATH . $this->config["rootpath"] . "/include/$plugin.php") ) {
                if ( $right = @include XOOPS_ROOT_PATH . $this->config["rootpath"] . "/include/$plugin.php" ) {
                    $xoopsPlugins[$plugin] = $plugin;
                }
            }
        }
        return $xoopsPlugins;
    }

    function loadCss($css_file = 'style.css')
    {
        static $css_url, $css_path;

        if (!isset($css_url)) {
            $css_url = dirname( xoops_getcss($GLOBALS['xoopsConfig']['theme_set']) );
            $css_path = str_replace(XOOPS_THEME_URL, XOOPS_THEME_PATH, $css_url);
        }

        $css = array();
        $css[] = $css_url . '/' . $css_file;
        $css_content = file_get_contents( $css_path . '/' . $css_file );

        // get all import css files
        if ( preg_match_all("~\@import url\((.*\.css)\);~sUi", $css_content, $matches, PREG_PATTERN_ORDER) ) {
            foreach( $matches[1] as $key => $css_import ) {
                $css = array_merge( $css, $this->loadCss( $css_import) );
            }
        }
        return $css;
    }

  /**
  * Renders the tinyMCE
  * @return   string  $ret      The rendered HTML string
  **/
  function render()
  {
    static $rendered;
    if($rendered) return null;

    $rendered = true;

    $this->init();

    if( !empty($this->setting["callback"]) ) {
        $callback = $this->setting["callback"];
        unset($this->setting["callback"]);
    } else {
        $callback = "";
    }

    $ret = '<script language="javascript" type="text/javascript" src="' . XOOPS_URL . $this->rootpath . '/tinymce.min.js"></script>';
    $ret .= '<script language="javascript" type="text/javascript">
                tinyMCE.init({
            ';

foreach ($this->setting as $key => $val) {
         $ret .= $key . ":";
         if ($val === true) {
             $ret.= "true,";
         } elseif ($val === false) {
             $ret .= "false,";
         } else {
             $ret .= "'{$val}',";
         }
         $ret .= "\n";
     }
	  
//	 Ajout alain01 tinymce v4

	$chemin_array=parse_url(XOOPS_URL);
	$chemin_scheme =  $chemin_array["scheme"]; // http
	$chemin_host =  $chemin_array["host"]; // www.example.com  or // localhost
//	$chemin_path =  $chemin_array["path"]; // /myweb1
	if (!isset($chemin_array['path'])){
			$chemin_path = '';
	} else	{
			$chemin_path =  $chemin_array["path"];
	}
		
//	 $ret .='language_url : "'.$chemin_path.'/class/xoopseditor/tinymce4/tinymce/js/tinymce/langs/fr_FR.js",';
	
	$ret .= 'external_plugins: {';
	$ret .= '"qrcode": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/qrcode/plugin.min.js",';
	$ret .= '"youtube": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/youtube/plugin.min.js",';
	$ret .= '"alignbtn": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/alignbtn/plugin.min.js",';
	$ret .= '"chartextbtn": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/chartextbtn/plugin.min.js",';
	$ret .= '"xoops_code": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/xoops_code/plugin.min.js",';
	$ret .= '"xoops_quote": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/xoops_quote/plugin.min.js",';
	$ret .= '"xoops_tagextgal": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/xoops_tagextgal/plugin.min.js",';
	$ret .= '"codemirror": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/codemirror/plugin.min.js",';
	$ret .= '"filemanager": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/filemanager/plugin.min.js",';
	$ret .= '"responsivefilemanager": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/responsivefilemanager/plugin.min.js",';
	$ret .= '},';
    $ret .= "\n";
	
	$ret .= 'codemirror: { 
		indentOnInit: true, 
		path: "CodeMirror", 
		config: { 
			mode: "application/x-httpd-php", 
			lineNumbers: false 
		}, 
		jsFiles: [ 
			"mode/clike/clike.js", 
			"mode/php/php.js" 
		] 
	},';
	$ret .= "\n";
	
	$ret .= '"external_filemanager_path": "'.$chemin_path.'/class/xoopseditor/tinymce4/external_plugins/filemanager/",';
    $ret .= "\n";

	$ret .='templates: "'.$chemin_path.'/uploads/filemanager/templates/liste-templates.js",';
	$ret .= "\n";
// fin ajout alain01

    $ret .= 'relative_urls : false,
                remove_script_host : false, tinymceload : "1"});
                '.$callback.'
                function showMCE(id) {
                    if (tinyMCE.getInstanceById(id) == null) {
                        tinyMCE.execCommand("mceAddControl", false, id);
                    } else {
                        tinyMCE.execCommand("mceRemoveControl", false, id);
                    }
                }
                </script>
            ';
    return $ret ;
  }
}
