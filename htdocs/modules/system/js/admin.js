/**
 * Administration function
 *
 * LICENSE
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     system
 * @version     $Id:$
 */

/**
 * Change the status of an item (avatar, userrank, smilies) ajax post request
 *
 * Exemple:
 * setStatus( {fct:'avatars', op:'display', avatar_id:1, avatar_display:0}, 'avt1', 'admin.php' )
 *
 * @author   MusS
 *
 * @array   data    store of data
 * @string  img     id of image
 * @file    file    file to call
 */

$(document).ready(function() {

    $("a.help_view").click(function(){
        $("div#xo-system-help").slideToggle(1000);
		$("a.help_view").toggle();
		$("a.help_hide").toggle();
	});

   $("a.help_hide").click(function(){
		$("div#xo-system-help").slideToggle(1000);
		$("a.help_view").toggle();
		$("a.help_hide").toggle();
   });

    if('function' == typeof($("").tablesorter)){
        // Banners
        $("#xo-bannerslist-sorter").tablesorter({sortList: [[0,0]], headers: {5:{sorter: false}}});
        $("#xo-bannersfinish-sorter").tablesorter({sortList: [[0,0]], headers: {6:{sorter: false}}});
        $("#xo-bannersclient-sorter").tablesorter({sortList: [[0,0]], headers: {4:{sorter: false}}});
        // Comments
        $("#xo-comment-sorter").tablesorter({sortList: [[2,0]], headers: { 1:{sorter: false}, 7:{sorter: false}}});
        // Groups
        $("#xo-group-sorter").tablesorter({sortList: [[0,0]], headers: {4:{sorter: false}}});
        // User Rank
        $("#xo-rank-sorter").tablesorter({sortList: [[0,0]], headers: {4:{sorter: false}, 5:{sorter: false}}});
        // Users
        $("#xo-users-sorter").tablesorter({sortList: [[2,0]], headers: {0:{sorter: false}, 1:{sorter: false}, 7:{sorter: false}}});
        // Smilies
        $("#xo-smilies-sorter").tablesorter({sortList: [[0,0]], headers: { 1:{sorter: false}, 3:{sorter: false}, 4:{sorter: false}}});
	}

});

function system_displayHelp() {
    $("div.panel_button").click(function(){
		$("div#panel").animate({
			height: "500px"
		})
		.animate({
			height: "400px"
		}, "fast");
		$("div.panel_button").toggle();

	});

   $("div#hide_button").click(function(){
		$("div#panel").animate({
			height: "0px"
		}, "fast");


   });
}

function system_setStatus( data, img, file ) {
    // Post request
    $.post( file, data ,
    function(reponse, textStatus) {
        if (textStatus=='success') {
			$('img#'+img).hide();
			$('#loading_'+img).show();
			setTimeout(function(){
				$('#loading_'+img).hide();
				$('img#'+img).fadeIn('fast');
			}, 500);
            // Change image src
            if ($('img#'+img).attr("src") == IMG_ON) {
                $('img#'+img).attr("src",IMG_OFF);
            } else {
                $('img#'+img).attr("src",IMG_ON);
            }
        }
    });
}

/**
 * Show dialog (system info)
 *
 * Exemple:
 * display_dialog(id, true, true, 'slide', 'slide', 240, 450)
 *
 *@author   Kraven30
 *
 * @string  id			id pop-pup
 * @string  bgiframe	bgiframe
 * @string  modal		modal
 * @string  hide		hide
 * @string  show		show
 * @string  height		height
 * @string  width		width
 */
function display_dialog(id, bgiframe, modal, hide, show, height, width) {
    $(document).ready(function(){
            $("#dialog"+id).dialog({
    			bgiframe: bgiframe,
    			modal: modal,
    			hide: hide,
    			show: show,
    			height: height,
    			width: width,
                autoOpen: false
    		});
    		$("#dialog"+id).dialog("open");
    });
}
function xo_toggle(object) {
    $(object).toggle();
}

function fadeOut(object){
    $(object).fadeOut('slow');
}

function fadeIn(object){
    $(object).fadeIn('slow');
}

/**
 * Display select groups
 *
 * @author  Kraven30
 * @Example changeDisplay (value, option ,display_id);
 */
function changeDisplay (value, option ,display_id)
{
	if(value == option) {
		document.getElementById(display_id).style.display = "";
	} else {
		document.getElementById(display_id).style.display = "none";
		document.getElementById('selgroups').style.display = "none";
	}
}

/**
 * Display block preview
 *
 * @author  MusS
 * @Example blocks_preview();
 */
function blocks_preview() {
    var queryString = $('#blockform').formSerialize();

    $.post( 'admin.php?type=preview', queryString ,
    function(reponse, textStatus) {
        if (textStatus=='success') {
            $("#xo-preview-block").html(reponse);
            $("#xo-preview-dialog").dialog({ modal: true});
            if (!$("#xo-preview-dialog").dialog( 'isOpen' )) {
            $("#xo-preview-dialog").dialog({
    			modal: modal,
    			hide: true,
    			show: true,
                autoOpen: false
    		});
    		$("#xo-preview-dialog").dialog("open");
		}
        }
    });
}

/**
 * Synchronise user post
 *
 * @author  Kraven30
 */
function display_post(uid)
{
	$('#display_post_'+uid).hide();
	$("#loading_"+uid).show();
	$.ajax({
		type: "POST",
		url: "./admin/users/jquery.php",
		data: "op=display_post&uid="+uid,
		success: function(msg){
			$('#display_post_'+uid).html(msg);
			$('#loading_'+uid).hide();
			$("#display_post_"+uid).fadeIn('fast');
		}
	});
}
function system_switchModsView (c)
{
    switch(c) {
        case 'large': default:
            system_moduleLargeView();
            break;
        case 'list':
            system_moduleListView();
            break;
    }
}

function system_moduleLargeView ()
{
    $('.xo-logonormal').fadeIn('fast');
    $('.xo-mods').addClass('hide');
    $('.xo-modsimages').removeClass('xo-actions');

    system_eraseCookie('xoopsModsView');
    system_createCookie('xoopsModsView', 'large', 365);
}

function system_moduleListView ()
{
    $('.xo-logonormal').fadeOut('fast');
    $('.xo-mods').removeClass('hide');
    $('.xo-modsimages').addClass('xo-actions');

    system_eraseCookie('xoopsModsView');
    system_createCookie('xoopsModsView', 'list', 365);
}

// cookie functions http://www.quirksmode.org/js/cookies.html
function system_createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function system_readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function system_eraseCookie(name)
{
	system_createCookie(name,"",-1);
}
// /cookie functions