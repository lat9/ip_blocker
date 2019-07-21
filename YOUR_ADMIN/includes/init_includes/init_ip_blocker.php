<?php
// -----
// Initialization script for the IP Blocker (ZC v1.5.6+)
//
// Copyright (C) 2014-2019, Vinos de Frutas Tropicales (lat9)
//
// @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
// -----

define('IP_BLOCKER_VERSION', '2.2.0-beta1');

// -----
// Register the IP Blocker tool into the admin menu structure.
//
if (!zen_page_key_exists('toolsIPblocker')) {
    zen_register_admin_page('toolsIPblocker', 'BOX_TOOLS_IP_BLOCKER', 'FILENAME_IP_BLOCKER', '', 'tools', 'Y', 20);
}    

// -----
// If the IP blocker was not previously installed, create the ip_blocker table.
//
if (!$sniffer->table_exists(TABLE_IP_BLOCKER)) {
    $db->Execute(
        "CREATE TABLE " . TABLE_IP_BLOCKER . " (
            ib_id tinyint(1) unsigned NOT NULL auto_increment,
            ib_blocklist longtext,
            ib_passlist longtext,
            ib_password varchar(50) NOT NULL default '',
            ib_power tinyint(1) default 1,
            ib_date date NOT NULL default '0001-01-01',
            ib_lockout_count int(5) NOT NULL default 0,
            ib_block_invalid_ip tinyint(1) default 1,
            ib_version varchar(32),
            PRIMARY KEY (ib_id) ) DEFAULT CHARSET=" . DB_CHARSET
    );
    $db->Execute("INSERT INTO " . TABLE_IP_BLOCKER . " (ib_date) VALUES('" . date('Y-m-d') . "')");
} else {
    // -----
    // If the IP blocker was previously installed, but the login lockout count field doesn't exist,
    // add it.
    //
    if (!$sniffer->field_exists(TABLE_IP_BLOCKER, 'ib_lockout_count')) {
        $db->Execute("ALTER TABLE " . TABLE_IP_BLOCKER . " ADD ib_lockout_count int(5) NOT NULL default 0");
    }
    
    // -----
    // v2.2.0: 
    // - Remove (unused) ip_blocklist_string and ip_passlist_string fields.
    // - Add a setting to enable unknown/invalid IP addresses to be blocked automatically.
    // - Add a version string for possible future updates.
    //
    if ($sniffer->field_exists(TABLE_IP_BLOCKER, 'ib_blocklist_string')) {
        $db->Execute(
            "ALTER TABLE " . TABLE_IP_BLOCKER . " 
                DROP COLUMN ib_blocklist_string, 
                DROP COLUMN ib_passlist_string"
        );
        $db->Execute(
            "ALTER TABLE " . TABLE_IP_BLOCKER . "
                ADD ib_block_invalid_ip tinyint(1) default 1,
                ADD ib_version varchar(32)"
        );
    }
}

$db->Execute("UPDATE " . TABLE_IP_BLOCKER . " SET ib_version = '" . IP_BLOCKER_VERSION . "'");
