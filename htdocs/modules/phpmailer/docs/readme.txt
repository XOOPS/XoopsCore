Read Me First
-------------------

Description
------------
The PHPMailer Extension Module provides email services for XOOPS using the PHPMailer library.

Requirements
 _____________________________________________________________________

- PHP version >= 7.1
- XOOPS 2.6.0+

Install/uninstall
------------------
No special measures necessary, follow the standard installation process & extract the gravatars folder into the modules/ directory. Install the module through Admin -> Extensions administration.

Detailed instructions on installing modules are available in the XOOPS Operations Manual (http://goo.gl/adT2i)

Operating instructions
------------------------
To set up this extension you need to:

i)   Configure your preferences for the module (see "Preferences")

ii)  The PHPMailer Extension is a service provider for both the Email and the UserEmailMessage services. If more than one provider for a given service is installed, you should use Service Management tool in System Administration to make sure your preferred provider is selected.
