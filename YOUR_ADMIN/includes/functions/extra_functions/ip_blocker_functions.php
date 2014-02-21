<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 The zen-cart developers                           |
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
// $Id: ip_blocker_functions.php, v1.0.0.0 2009/09/09 $d <noblesenior@gmail.com> $
// ------------------------------
// Modifications for Zen Cart v1.5.0+ by lat9, Copyright 2014, Vinos de Frutas Tropicales
// ------------------------------


// -----
// Convert the IP Blocker's "visual" form of the IP list to the serialized version that's used to compare
// a store-side IP address for the block/pass lists.  Will not update the database if an invalid ipV4 IP
// address is discovered.
//
function ip_blocker_ip_list ($list, $type = 'block') {
  global $db;
  
  $iplist = trim ($list);
  $column = "ib_{$type}list";
  $column_string = $column . '_string';
  $error_ip = '';
  if ($iplist == '') {
    // Clear ip list
    $db->Execute('UPDATE ' . TABLE_IP_BLOCKER . " SET $column = '',  $column_string = '', ib_date = '" . date('Y-m-d') . "' WHERE ib_id=1");
    
  } else {
    $ip = array();
    $ip_rows = explode("\r\n", $iplist);
    if (!empty ($ip_rows)) {
      foreach ($ip_rows as $ip_row){
        if (!ip_blocker_validate_address ($ip_row, $ip_info)) {
          $error_ip = $ip_row;
          break;
        }
       
        if (!isset ($ip_info['range'])) {
          $ip[] = $ip_row;
          
        } else {
          $ip_pre = $ip_info[0] . '.' . $ip_info[1] . '.' . $ip_info[2] . '.';
          for ($range = $ip_info['range'][0]; $range <= $ip_info['range'][1]; $range++) {
            $ip[] = "$ip_pre.$range";
            
          }
          
        }
      }
    }
    
    if ($error_ip == '') {
      $ip_list_string = @zen_db_input (serialize($ip));
      $db->Execute('UPDATE ' . TABLE_IP_BLOCKER . " SET $column = '$ip_list_string', $column_string = '$iplist', ib_date = '" . date('Y-m-d') . "' WHERE ib_id=1");
    
    }
    
  }  // IP list supplied is not empty
  
  return $error_ip;
  
}

// -----
// Validate a single IP-address specification.  Valid:
//
// - 192.16.1.255
// - 192.168.1.*
// - 192.168.2.3/6
//
// Invalid:
// - 888.16.1.255
// - 192.168.*
// - 192.168.2.6/3
//
function ip_blocker_validate_address ($ip, &$ip_info) {
  $is_valid = true;

  $ip_info = explode ('.', $ip);
  if (count ($ip_info) != 4) {
    $is_valid = false;
  } else {
    for ($i = 0; $i < 4 && $is_valid; $i++) {
      if (!(ctype_digit ($ip_info[$i]) && $ip_info[$i] >= 0 && $ip_info[$i] <= 255)) {
        if ($i != 3) {
          $is_valid = false;
          
        } else {
          if ($ip_info[$i] != '*') {
            $ip_info['range'] = explode ('/', $ip_info[$i]);
            if (count ($ip_info['range']) != 2) {
              $is_valid = false;
            } else {
              for ($j = 0; $j < 2 && $is_valid; $j++) {
                if (!(ctype_digit ($ip_info['range'][$j]) && $ip_info['range'][$j] >= 0 && $ip_info['range'][$j] <= 255)) {
                  $is_valid = false;
                }
              }
              if ($ip_info['range'][0] >= $ip_info['range'][1]) {
                $is_valid = false;
              }
            }
          }
        }
      }
    }
  }
  return $is_valid;
}
