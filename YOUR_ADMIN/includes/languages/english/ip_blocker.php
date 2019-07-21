<?php
// -----
// Part of the "IP Blocker" plugin, provided by lat9 and torvista.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales.
//
// $Id: ip_blocker.php, v1.0.0 2009/09/09 $d <noblesenior@gmail.com> $
//
define('HEADING_TITLE', 'IP Blocker (v%s)');
define('IB_TEXT_INSTRUCTIONS', '<p>The IP Blocker prevents access to the storefront of the shop from the IP addresses defined below. Blocked IP addresses are redirected to a special login/password page which may be used to bypass the block.</p><p>For testing purposes, note that an IP address will not be blocked if a session has already been initiated from that IP address. To test, delete the zenid cookie from the browser.</p>');
define('IB_TEXT_ENABLE', 'Enable IP Blocker?');

define('IB_TEXT_SPECIAL_LOGIN_PASSWORD', 'Special Login Page Password');
define('IB_TEXT_PASSWORD_INFO', 'Identify the password that can be used to cancel the <code>special_login</code> page. After update, the password information is no longer displayed &mdash; so be sure you remember what you entered!  If you enter the password as an empty, blank, value then there is <b>no exit</b> from that page, i.e. no password entered will match.');

define('IB_TEXT_SPECIAL_LOGIN_COUNT', 'Special Login Page Count');
define('IB_TEXT_SPECIAL_LOGIN_COUNT_INFO', 'The <em>Special Login count</em> is the number of unsuccessful login attempts allowed from the &quot;Special Login&quot; page before the page stops taking input and simply presents a white-screen.  A value of 0 in the count permits unlimited attempts.');

define('IB_AUTO_BLOCK_INVALID_IPS', 'Automatically block &quot;invalid&quot; IP addresses?');
define('IB_AUTO_BLOCK_INFO', "The built-in Zen Cart storefront identifies invalid IP addresses as a single dot (<code>.</code>). Choose <em>Yes</em> if those addresses should be automatically blocked.");

define('IB_TEXT_BLOCKED_RANGE', 'Blocked IP Addresses:');
define('IB_TEXT_ALLOWED_RANGE', 'Allowed IP Addresses:');

define('IB_TEXT_IP_ADDRESS', 'IP Addresses');
define('IB_TEXT_IP_ADDRESS_INFO', 'Enter a single IP address or a range of IP addresses, each on a single line. Address ranges can be specified using an asterisk to identify all values in that segment, e.g. <code>192.168.1.*</code>, or you can define a more specific range, e.g. <code>192.168.1.1/17</code>.');

define('IB_ERROR_MESSAGE_BAD_IP', 'Invalid IP Address: %s');
define('IB_MESSAGE_UPDATED', 'The IP Blocker settings have been updated.');