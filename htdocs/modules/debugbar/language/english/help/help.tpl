<h4>Description</h4>
<p>
    DebugBar is an error reporting and performance analysis tool. It attaches to the PSR-3 compatible Xoops Logger interface, and feeds into the PHP DebugBar by Maxime Bouroumeau-Fuseau from <a href="http://phpdebugbar.com/" target="_blank">http://phpdebugbar.com/</a>.
</p>
<h4>Preferences</h4>
<p>
    The DebugBar preferences establish the default settings for the DebugBar. The options are:
    <ul>
        <li><strong>Display DebugBar</strong> - displays the debug bar at the bottom of each page.</li>
        <li><strong>Enable Smarty Debug</strong> - displays Smarty debug information in a popup window.</li>
    </ul>

    These settings are the system defaults. Authorized users can set their individual preferences in User Configs, if it is installed.
</p>
<h4>Permissions</h4>
<p>
    Permission to use the DebugBar is established by XOOPS Groups. Select the groups which can use the DebugBar. Be aware that there are security implications to revealing the system details that can be revealed in the logs.
</p>
<h4>DebugBar Panels</h4>
<p>
    <li><strong>Messages</strong> - Error and warning messages.</li>
    <li><strong>Request</strong> - PHP Request variables ($_GET, $_POST, etc.)</li>
    <li><strong>Timeline</strong> - Timing information</li>
    <li><strong>Exceptions</strong> - Any logged PHP exceptions</li>
    <li><strong>Deprecated</strong> - Warnings about usage of deprecated functions</li>
    <li><strong>Blocks</strong> - A list of blocks processed, with cache useage and timing.</li>
    <li><strong>Extra</strong> - Extra information such as files used, and module helper debug logs.</li>
    <li><strong>Database</strong> - SQL query and timing information</li>
    <li><strong>Smarty</strong> - a listing of defined Smarty variables.</li>
</p>
<h4>Smarty Debug</h4>
<p>
     Additional information is available by using the <em>Enable Smarty Debug</em> option. This information is also available if the DebugBar is enabled by including a "SMARTY_DEBUG" (case sensitive) named variable in the page url (i.e. index.php?SMARTY_DEBUG)
</p>