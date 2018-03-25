<h4>Description</h4>
<p>
    The PHPMailer extension implements both a Email service provider, and a UserEmailMessage service provider.
    The Email service provider can be used to send standard emails, including multiple recipients, attachments, etc.
    The UserEmailMessage service provider is a simple message from one system user to another user, similar to
    Private Messages, but transported via email.
</p>

<p>
    The PHPMailer extension uses the PHPMailer library for building and sending emails.
</p>

<h4>Installation</h4>
<p>
    The PHPMailer extension installs like any other XOOPS extension. If multiple Email service providers are
    installed, use the Administration -> Service Management page to ensure the correct desired provider is used.
</p>

<dl>
    <dt>Email delivery method</dt>
    <dd>Email transport method. Possible values are the PHP mail() function, system sendmail program, an SMTP server, with or without auth requirements.</dd>

    <dt>SMTP host(s)</dt>
    <dd>A semicolon (;) separated list of SMTP servers to use (only used for SMTP transports).
        A single host consists of a host name, with optional port number. A tls:// or ssl:// scheme
        can also be specified. Example: tls://smtp.example.com:587;localhost:25</dd>

    <dt>SMTPAuth password</dt>
    <dd>Password to connect to an SMTP host with SMTPAuth. (only used for SMTP Auth transport)</dd>

    <dt>SMTPAuth username</dt>
    <dd>Username to connect to an SMTP host with SMTPAuth. (only used for SMTP Auth transport)</dd>

    <dt>Use TLS if supported</dt>
    <dd>Choose "Yes" to automatically use TLS if server advertises it is available. Use "No" to disable automatic behavior. (only used for SMTP transports)</dd>

    <dt>Enable SMTP debugging</dt>
    <dd>Log extra output from SMTP to the system log. (only used for SMTP transports)</dd>

    <dt>Path to sendmail</dt>
    <dd>Path to the sendmail program (or substitute) on the webserver. (only used for sendmail transport)</dd>

</dl>

<h4>Programmer Notes</h4>
<p>
    XOOPS defines a standard Email model which can be used, along with related address and attachment models, to
    fully represent an email message. These standard data objects are used to communicate with the Email and service
    provider.
</p>
<dl>
    <dt>Xoops\Core\Service\Data\Email</dt>
    <dd>An Email message, including the various addresses types and attachments.</dd>

    <dt>Xoops\Core\Service\Data\EmailAddress</dt>
    <dd>A validating data object representing an email address</dd>

    <dt>Xoops\Core\Service\Data\EmailAddressList</dt>
    <dd>A validating data object representing a list of email address</dd>

    <dt>Xoops\Core\Service\Data\EmailAttachment</dt>
    <dd>A validating data object representing an attachment</dd>

    <dt>Xoops\Core\Service\Data\EmailAttachmentSet</dt>
    <dd>A validating data object representing a set of email attachments</dd>

    <dt>\Xoops\Core\Service\Data\Message</dt>
    <dd>A minimal plain text message from one system user to another user, used by the UserEmailMessage and UserMessage services.</dd>
</dl>
