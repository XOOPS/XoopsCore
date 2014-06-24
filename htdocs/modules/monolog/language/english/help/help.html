<h4>Description</h4>
<p>
    The monolog module is a logging application that attaches to the <a href="http://www.php-fig.org/psr/3/" target="_blank">PSR-3</a> compatible Xoops Logger interface. The monolog module feed to logs controlled by Jordi Boggiano's Monolog pacakge: <a href="https://github.com/Seldaek/monolog" target="_blank">https://github.com/Seldaek/monolog</a>. This logger makes remote debugging available at a level never before possible with XOOPS.
</p>
<p>
    When all logging options are enabled, this logger collects the same information as the traditional XOOPS logger, but in the order it happens. It is also not limited to being used in a privileged user session. The primary output of this logger is to a file, and that file collects logging data from the entire application, not just one user session instance. Using this, you can see the logging information that the traditional logger would have provided, if it had been on while a remote user was accessing your site. There is no special user permission required, as the user's actvities are not changed by the activation of the monolog logger.
</p>
<p>
    The Monolog logger  can create logs of all system activity from all users. As you can imagine, this can be a substantial amount of data, and such broad use should not be applied without consideration of the impact to your system and storage quotas.
</p>

<h4>Preferences</h4>
<p>
    The Monolog preferences are used to establish a basic logging system. The options are:
    <ul>
        <li><strong>Enable Logging</strong> - </li>
        <li><strong>Log level threshold</strong> - </li>
        <li><strong>Absolute path and name of log file</strong> - </li>
        <li><strong>Include blocks</strong> - </li>
        <li><strong>Include deprecated</strong> - </li>
        <li><strong>Include extra</strong> - </li>
        <li><strong>Include queries</strong> - </li>
        <li><strong>Include timers</strong> - </li>
    </ul>
Authorized users can set an individual preference in User Configs, if it is installed, to control echoing the log of the current session to the browser console using the FirePHP protocol.
</p>
<h4>Permissions</h4>
<p>
    Permission to use the FirePHP feature is established by XOOPS Groups. Select the groups which can use the FirePHP feature. Be aware that there are security implications to revealing the system details that can be contained in the logs.
</p>
<h4>Log file</h4>
<p>
    All information is recorded to the log file specified in preferences. That file is a simple flat file with one log record per line. The line can contains very detailed information; here is an example:
    <pre>
    [2013-09-12 22:15:25] app.INFO: Blocks : User menu: Not cached
    {"channel":"Blocks","cached":"false","cachetime":"0"}
    {"user":"username","url":"/xoopscore/modules/demo/debugbar.php",
    "ip":"127.0.0.1","http_method":"GET","server":"localhost","referrer":"NULL"}

    </pre>
    Here is an explaination of each data element.
    <ul>
        <li><strong>[2013-09-12 22:15:25]</strong> - the entry timestamp</li>
        <li><strong>app.INFO:</strong> - indicates this is an INFO level message on the 'app' channel. (There may be other channels, such as a security channel for Protector logs.)</li>
        <li><strong>Blocks : User menu: Not cached</strong> - This is the actual message text</li>
        <li><strong>{"channel":...}</strong> - This is the detailed variable set used to build the message. These are exposed as such for use in log querying tools.</li>
        <li><strong>"user":"username"</strong> - The XOOPS user generating this entry</li>
        <li><strong>"url":"/xoopscore/modules/test/index.php"</strong> - URL generating this entry</li>
        <li><strong>"ip":"127.0.0.1"</strong> - The IP address from which this session originated</li>
        <li><strong>"http_method":"GET"</strong> - The method, GET or POST</li>
        <li><strong>"server":"localhost""</strong> - The server name</li>
        <li><strong>"referrer":"NULL"</strong> - The refering URL if available</li>
    </ul>
</p>
<h4>Notes For Module Programmers</h4>
<p>
    For many years, the most common method for a module to communicate to the log was by using the PHP <em>trigger_error()</em> function. With the implmentation of the 2.6.0 Xoops Logger, any module can communicate not only a message, but the type and severity of the message in a consistent way. Here are examples:
    <pre> Xoops::getInstance()->logger()->error('detailed message here');</pre>
    or
    <pre> Xoops::getInstance()->logger()->debug('detailed message here');</pre>
    The available methods are as described in <a href="http://www.php-fig.org/psr/3/" target="_blank">PSR-3</a>. Most modules will need only a subset of the methods:

    <ul>
    <li><strong>error()</strong> - Runtime errors that do not require immediate action but should typically be logged and monitored.</li>
    <li><strong>warning()</strong> - Exceptional occurrences that are not errors. Example: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.</li>
    <li><strong>notice()</strong> - Normal but significant events.</li>
    <li><strong>info()</strong> - Interesting events.</li>
    <li><strong>debug()</strong> - Detailed debug information.</li>
    </ul>

    In addtion to the error message, you can also specify an array of context information, related to the message. This array is included in the log file entry.
</p>
