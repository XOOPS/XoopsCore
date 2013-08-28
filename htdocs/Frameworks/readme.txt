XOOPS Frameworks

For common frameworks shared by XOOPS modules.

A framework could be adopted into XOOPS core once it is proven.

The structure:
{XOOPS_ROOT_PATH}/Frameworks/myframework/xoopsmyframework.php

Content of myframework.php:
<?php

class XoopsMyframework
{
    function __construct()
    {
    }
    ...
}

?>