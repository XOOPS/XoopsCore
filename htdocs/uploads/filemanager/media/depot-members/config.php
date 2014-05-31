<?php

//------------------------------------------------------------------------------
// YOU CAN COPY AND CHANGE THESE VARIABLES IN FOLDERS config.php FILES
//------------------------------------------------------------------------------
// $image_max_width=0;
$image_max_width=1000;

// XOOPS
global $xoopsUser; 
// 1 : webmasters
// 2 : members
// 3 : anonymous
// 4 : your new group
$allowed_groups_upload=array(1,2); // id des groupes autorisés en upload
$allowed_groups_createfolder=array(1); // id des groupes autorisés create folder
if ($xoopsUser) {  
    $usergroups = $GLOBALS['xoopsUser']->getGroups(); 
    $result_upload = array_intersect($usergroups, $allowed_groups_upload); 
        if ($result_upload || $result_upload!=null) { 
           $upload_files=true; 
        } 
  $result_createfolder = array_intersect($usergroups, $allowed_groups_createfolder); 
       if ($result_createfolder || $result_createfolder!=null) { 
          $create_folders=true;
		} 
}
// XOOPS
	



?>
