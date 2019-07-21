<?php
/**
 * IP Blocker functions
 *
 * @package functions
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: ip_blocker.php , v1.0.0 2009/09/09 $d <noblesenior@gmail.com> $
 */
// --------------------
// v2.0.0 for Zen Cart v1.5.0+, reworked as an init_script by lat9@vinosdefrutastropicales.com
// --------------------

// -----
// Check if the IP submitted is in the IP block-list.  Starting with v2.2.0, check
// also to see if invalid IP addresses should be automatically blocked.
//
function ip_blocker_block($ip)
{
    global $db;

    $query = $db->Execute('SELECT * FROM `' . TABLE_IP_BLOCKER . '` WHERE ib_id=1');
    
    if ($query->fields['ib_block_invalid_ip'] == 1 && filter_var((string)$_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_IPV4) === false) {
        return true;
    }
    
    $blocklist = $query->fields['ib_blocklist'];
    $blocklist = empty($blocklist) ? array() : unserialize($blocklist);

    
    if (!is_array($blocklist) || empty($blocklist)) {
        return false;
    }

    foreach ($blocklist as $block) {
        if ($ip == $block || strpos($ip, str_replace('*', '', $block)) === 0) {
            return true;
        }
    }
    return false;
}

// -----
// Check if the IP submitted is in the IP pass-list
//
function ip_blocker_pass($ip)
{
    global $db;

    $passlist = $db->Execute('SELECT * FROM `' . TABLE_IP_BLOCKER . '` WHERE ib_id=1');
    $passlist = $passlist->fields['ib_passlist'];
    $passlist = (empty($passlist)) ? array() : unserialize($passlist);

    if (!is_array($passlist) || empty($passlist)) {
        return false;
    }

    foreach ($passlist as $pass) {
        if ($ip == $pass || strpos($ip, str_replace('*', '', $pass)) === 0) {
            return true;
        }
    }
    return false;
}

// -----
// If the special login script (the IP blocker) is *not* running and the IP blocker is installed ...
//
if (strpos($_SERVER['SCRIPT_NAME'], 'special_login') === false && $sniffer->table_exists(TABLE_IP_BLOCKER)) {
    $ib_result = $db->Execute('SELECT * FROM `' . TABLE_IP_BLOCKER . '` WHERE ib_id=1');

    // -----
    // ... and enabled ...
    //
    if (!$ib_result->EOF && ((bool)$ib_result->fields['ib_power'])) {
        // -----
        // ... and the current IP has either not yet been checked or has not passed the block-check ...
        //
        if (!isset($_SESSION['ip_blocker_pass']) || $_SESSION['ip_blocker_pass'] !== true) {
            $ip = (string)zen_get_ip_address();

            // -----
            // ... and the IP is not in the pass-list but is in the blocked list, transfer control to the IP blocker's "special" login page.
            //
            if (!ip_blocker_pass($ip) && ip_blocker_block($ip)) {
                zen_redirect('special_login.php');
            }

            $_SESSION['ip_blocker_pass'] = true;
        }
    }
}
