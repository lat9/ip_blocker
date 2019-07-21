<?php
/// -----
// Part of the "IP Blocker" plugin, provided by lat9 and torvista.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales.
/*
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: ip_blocker_database_tables.php, v1.0.0.0 2009/09/09 $d <noblesenior@gmail.com> $
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

define('TABLE_IP_BLOCKER', DB_PREFIX . 'ip_blocker');

// -----
// Use this constant to define the text you want your store to display at the top of the
// "special_login" page.
//
define('TEXT_SPECIAL_LOGIN_INSTRUCTIONS', 'Your IP address has been blocked from this site ...');