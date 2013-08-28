// jQuery File Tree Plugin
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// Visit http://abeautifulsite.net/notebook.php?article=58 for more information
//
// Usage: $('.fileTreeDemo').fileTree( options, callback )
//
// Options:  root           - root folder to display; default = /
//           script         - location of the serverside AJAX file to use; default = jqueryFileTree.php
//           folderEvent    - event to trigger expand/collapse; default = click
//           expandSpeed    - default = 500 (ms); use -1 for no animation
//           collapseSpeed  - default = 500 (ms); use -1 for no animation
//           expandEasing   - easing function to use on expand (optional)
//           collapseEasing - easing function to use on collapse (optional)
//           multiFolder    - whether or not to limit the browser to one subfolder at a time
//           loadMessage    - Message to display while initial tree loads (can be HTML)
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// TERMS OF USE
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC. 
//
if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			// Defaults
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '/';
			if( o.script == undefined ) o.script = 'jqueryFileTree.php';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					$.post(o.script, { dir: t }, function(data) {
						$(c).find('.start').html('');
						$(c).removeClass('wait').append(data);
						if( o.root == t ) $(c).find('UL:hidden').show(); else $(c).find('UL:hidden').slideDown({ duration: o.expandSpeed, easing: o.expandEasing });
						bindTree(c);
					});
				}
				
				function bindTree(t) {
					$(t).find('LI A').bind(o.folderEvent, function() {
						if( $(this).parent().hasClass('directory') ) {
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand
								if( !o.multiFolder ) {
									$(this).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
									$(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
								}
								$(this).parent().find('UL').remove(); // cleanup
								showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
								$(this).parent().removeClass('collapsed').addClass('expanded');
							} else {
								// Collapse
								$(this).parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
								$(this).parent().removeClass('expanded').addClass('collapsed');
							}
						} else {
							h($(this).attr('rel'));
						}
						filemanager_display_file(this, 0);
						return false;
					});
					// Prevent A from triggering the # on non-click events
					if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
				}
				// Loading message
				$(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
				// Get the initial file list
				showTree( $(this), escape(o.root) );
			});
		}
	});
	
})(jQuery);

//Display Tree
$(document).ready( function() {
	filemanager_load_tree();
});

function filemanager_load_tree() {
    $('#fileTree').fileTree({ 
        script: 'admin/filemanager/jquery.php?op=filemanager_display_folder', 
        folderEvent: 'click', 
        expandSpeed: 750, 
        collapseSpeed: 750, 
        multiFolder: false 
    });
}

// Display file	
function filemanager_display_file(dir, status)
{
	$('#edit_file').hide();
	$('#upload_file').hide();
	$('#display_file').hide();
	$('#confirm_delete').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_display_file&file="+dir+"&status="+status,
		success: function(msg){
			$('#display_file').html(msg);
			$('#loading').hide();
			$('#display_file').fadeIn('fast');
		}
	});	
	return false;
}

// Edit
function filemanager_edit_file(path_file, path, file, extension)
{
	$('#display_file').hide();
	$('#edit_file').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_edit_file&path_file="+path_file+"&path="+path+"&file="+file,
		success: function(msg){
			$('#edit_file').html(msg);
			$('#loading').hide();
			filemanager_code_mirror(extension);
			$('#edit_file').fadeIn('fast');
		}
	});	
	return false;
}

//Edit
function filemanager_unzip_file(path_file, path, file)
{
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_unzip_file&path_file="+path_file+"&path="+path+"&file="+file,
		success: function(msg){
            $('#display_file').html(msg);
		}
	});	
	return false;
}

//Confirm Delete
function filemanager_confirm_delete_file(path_file, path, file)
{
	$('#display_file').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_confirm_delete_file&path_file="+path_file+"&path="+path+"&file="+file,
		success: function(msg){
			$('#confirm_delete').html(msg);
			$('#loading').hide();
			$('#confirm_delete').fadeIn('fast');
		}
	});	
	return false;
}

//Delete
function filemanager_delete_file(path_file, path)
{
	$('#confirm_delete').hide();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_delete_file&path_file="+path_file+"&path="+path,
		success: function(msg){
			$('#display_file').html(msg);
			$('#display_file').show();
			setTimeout(function(){
                $('#confirm_delete').hide();
				filemanager_load_tree(); 
				filemanager_display_file('', 0)
            }, 2000);
		}
	});	
	return false;
}

// Upload
function filemanager_upload(path)
{
	$('#display_file').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_upload&path="+path,
		success: function(msg){
			$('#upload_file').html(msg);
			$('#loading').hide();
			$('#upload_file').fadeIn('fast');
		}
	});	
	return false;
}		

function filemanager_add_directory(path) {
	$('#display_file').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_add_dir&path="+path,
		success: function(msg){
			$('#upload_file').html(msg);
			$('#loading').hide();
			$('#upload_file').fadeIn('fast');
		}
	});	
	return false;
}

//Confirm Delete
function filemanager_confirm_delete_directory(path)
{
	$('#display_file').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_confirm_delete_directory&path="+path,
		success: function(msg){
			$('#confirm_delete').html(msg);
			$('#loading').hide();
			$('#confirm_delete').fadeIn('fast');
		}
	});	
	return false;
}

function filemanager_delete_directory(path)
{
	$('#confirm_delete').hide();
	$('#display_file').hide();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_delete_directory&path="+path,
		success: function(msg){
			$('#display_file').html(msg);
			$('#display_file').show();
			setTimeout(function(){
                $('#confirm_delete').hide();
				filemanager_load_tree(); 
				filemanager_display_file('', 0)
            }, 2000);
		}
	});	
	return false;
}

function filemanager_add_file(path) {
	$('#display_file').hide();
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_add_file&path="+path,
		success: function(msg){
			$('#upload_file').html(msg);
			$('#loading').hide();
			$('#upload_file').fadeIn('fast');
		}
	});	
	return false;
}

//Chmod
function filemanager_modify_chmod(path_file, id)
{
	select = document.getElementById("chmod");
	chmod = select.options[select.selectedIndex].value
	$('#chmod'+id).hide();
	$('#loading_'+id).show();
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_modify_chmod&path_file="+path_file+"&chmod="+chmod+"&id="+id,
		success: function(msg){
            $('#chmod'+id).html(msg);
			$('#loading_'+id).hide();
			$('#chmod'+id).fadeIn('fast');
		}
	});	
	return false;
}

//Restore
function filemanager_restore(path_file)
{
	$.ajax({
		type: "POST",
		url: "./admin/filemanager/jquery.php",
		data: "op=filemanager_restore&path_file="+path_file,
		success: function(msg){
            $('#edit_file').fadeOut('fast');
			$('#display_file').html(msg);
			filemanager_load_tree();
			filemanager_display_file('', 0)
		}
	});	
	return false;
}

//Code mirror
function filemanager_code_mirror(extension) {
	if(extension == "css") {
		var editor = CodeMirror.fromTextArea("code_mirror", {    
			height: "350px",
			parserfile: "parsecss.js",
			stylesheet: "css/code_mirror/csscolors.css",                    
			lineNumbers: true,
			textWrapping: false, 
			path: "js/code_mirror/"                
		});
	} else if(extension == "js") {
		var editor = CodeMirror.fromTextArea("code_mirror", {
			height: "350px",
			parserfile: ["tokenizejavascript.js", "parsejavascript.js"],
			stylesheet: "css/code_mirror/jscolors.css",
			autoMatchParens: true,
			lineNumbers: true,
			textWrapping: false, 
			path: "js/code_mirror/"
		});
	} else if(extension == "html") {
		var editor = CodeMirror.fromTextArea("code_mirror", {
			height: "350px",
			parserfile: "parsexml.js",
			stylesheet: "css/code_mirror/xmlcolors.css",
			path: "js/code_mirror/",
			continuousScanning: 500,
			lineNumbers: true
		});
	} else if(extension == "sql") {
		  var editor = CodeMirror.fromTextArea("code_mirror", {
			height: "350px",
			parserfile: "parsesql.js",
			stylesheet: "css/code_mirror/sqlcolors.css",
			path: "js/code_mirror/",
			textWrapping: false
		  });
	}else{
		var editor = CodeMirror.fromTextArea("code_mirror", {
			height: "350px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
						"tokenizephp.js", "parsephp.js",
						"parsephphtmlmixed.js"],
			stylesheet: ["css/code_mirror/xmlcolors.css", "css/code_mirror/jscolors.css", "css/code_mirror/csscolors.css", "css/code_mirror/phpcolors.css"],
			lineNumbers: true,
			textWrapping: false, 
			path: "js/code_mirror/",
			continuousScanning: 500
		});
	} 
}
