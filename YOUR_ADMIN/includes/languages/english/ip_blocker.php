<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: ip_blocker.php, v1.0.0 2009/09/09 $d <noblesenior@gmail.com> $
//
define('IP_BLOCKER_TITLE', 'IP Blocker (v2.0.1)');
define('IP_BLOCKER_MENU_IP_SETTINGS', 'IP Settings');
define('IP_BLOCKER_MENU_PASSWORD_SETTINGS', 'Password Settings');
define('IP_BLOCKER_MENU_POWER', 'Enable');
define('IP_BLOCKER_MENU_INSTALL', 'Install');
define('IP_BLOCKER_MENU_UNINSTALL', 'Uninstall');

define('IB_TEXT_PASSWORD', 'Password:');

define('IP_BLOCKER_HELP_IP_SETTINGS', 'Use this page to set the IP addresses (or address ranges) that should be blocked from your store.  If an address is present in a blocked-range and is not present in an allowed-range, then the access attempt to your store will be blocked.<br /><br />Enter the IP addresses (or address ranges), one per line. Address ranges can be specified using an asterisk to identify all values in that segment, e.g. <code>192.168.1.*</code>, or you can identify a specific range, e.g. <code>192.168.1.1/17</code>.');
define('IB_BLOCKED_RANGE', 'Blocked IP Addresses');
define('IB_ALLOWED_RANGE', 'Allowed IP Addresses');

define('IP_BLOCKER_HELP_PASSWORD_SETTINGS_DEFAULT', 'You are currently using the default password (<b>123456</b>); you should change the password before you enable the IP blocker!');
define('IB_PASSWORD_SET', 'Your IP blocker password has been changed from the default value.  If you don\'t remember the value, you can change it here.');

define('IB_UNINSTALL_VERIFY', 'Are you sure you want to uninstall the IP Blocker?');
define('IB_REMOVED', 'IP Blocker has been successfully removed.');

define('IB_INSTALL_REQUIRED', 'IP Blocker is not currently installed.  Click the button below to install it now!');

define('IB_FEATURE_ENABLED', 'You have successfully enabled the IP Blocker!');
define('IB_FEATURE_DISABLED', 'You have successfully disabled the IP Blocker!');
define('IB_ON', 'On');
define('IB_OFF', 'Off');
define('IP_BLOCKER_HELP_POWER', 'Use this page to set the IP Blocker "On" or "Off".');

define('IB_INSTALL_OK', 'IP Blocker was successfully installed. The default password is <span style="color:blue;">123456</span>.');
define('IB_MESSAGE_IP_UPDATED_SUCCESS', 'The IP Address lists were successfully updated.');
define('IB_MESSAGE_PASSWORD_REQUIRED_ERROR', 'A password is required!');
define('IB_MESSAGE_PASSWORD_UPDATED_SUCCESS', 'The IP Blocker password was successfully updated.');