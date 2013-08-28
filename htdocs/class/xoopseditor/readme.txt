xoopseditor provides a collective of editors for XOOPS

user guide:

1 check the files xoops_version.php under /xoopseditor/ to make sure it is newer than your current ones

2 upload /xoopseditor/ to /XOOPS/class/ => /XOOPS/class/xoopseditor/:
  XOOPS/class/xoopseditor/dhtmlext
  XOOPS/class/xoopseditor/dhtmltextarea
  XOOPS/class/xoopseditor/fckeditor
  XOOPS/class/xoopseditor/koivi
  XOOPS/class/xoopseditor/textarea
  XOOPS/class/xoopseditor/tinymce
  
3 configure preferences where applicable
3.1 ./dhtmlext(all editors)/language/: make your local langauge file based on english.php
3.3 ./dhtmlext(all editors)/editor_registry.php: set configurations for the editor: order - display order in case editor selection is used, 0 for disabled; nohtml - works for non-html syntax
3.3 ./FCKeditor/module/: copy the files to the modules folders in case module specific upload permissions, storage and editor options are required
3.3.1 ./FCKeditor/module/fckeditor.config.js: for editor options, you usually don't not need to change it
3.3.2 ./FCKeditor/module/fckeditor.connector.php: to specify the folder for file browsing (and uploading storage) => XOOPS/uploads/XOOPS_FCK_FOLDER/, the folder is required to create manually
3.3.3 ./FCKeditor/module/fckeditor.upload.php: specify upload permission and uploading storage
3.4 XOOPS/uploads/fckeditor/: to create the folder if FCKeditor is enabled, used for uploads from where the upload folder is not specified
3.5 ./tinymce/tinymce/jscripts/: download your local language files from http://tinymce.moxiecode.com/language.php

4 check file names: for filename case sensitive system, make sure you have the file names literally correct, i.e., "FCKeditor" is not identical to "fckeditor"

5 check /xoopseditor/sampleform.inc.php for development guide