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
define('HEADING_TITLE', 'IP Blocker (v2.1.4)');
define('IB_TEXT_INSTRUCTIONS', '<p>The IP Blocker prevents access to the catalog-side of the shop from the IP addresses defined below.<br>Blocked IP addresses are redirected to a special login/password page which may be used to bypass the block.</p><p>For testing purposes, note that an IP address will not be blocked if a session has already been initiated from that IP address. To test, delete the zenid cookie from the browser.</p>');
define('IB_TEXT_ENABLE', 'Enable IP Blocker?');

define('IB_TEXT_SPECIAL_LOGIN_PASSWORD', 'Special Login Page Password');

define('IB_TEXT_SPECIAL_LOGIN_COUNT', 'Special Login Page Count');  //-v2.0.1a
define('IB_TEXT_SPECIAL_LOGIN_COUNT_INFO', '<p>The <em>Special Login count</em> is the number of unsuccessful login attempts allowed from the &quot;Special Login&quot; page before the page stops taking input and simply presents a white-screen.  A value of 0 in the count permits unlimited attempts.</p>');

define('IB_TEXT_BLOCKED_RANGE', 'Blocked IP Addresses:');
define('IB_TEXT_ALLOWED_RANGE', 'Allowed IP Addresses:');

define('IB_TEXT_IP_ADDRESS', 'IP Addresses');
define('IB_TEXT_IP_ADDRESS_INFO', '<p>Enter a single IP address or a range of IP addresses on a single line.<br>Address ranges can be specified using an asterisk to identify all values in that segment, e.g. <code>192.168.1.*</code>, or you can define a more specific range, e.g. <code>192.168.1.1/17</code>.</p>');

define('IB_ERROR_MESSAGE_BAD_IP', 'Invalid IP Address: %s');
define('IB_MESSAGE_UPDATED', 'The IP Blocker settings have been updated.');