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
// $Id: ip_blocker.php, v1.0.0 2009/09/09 $d <noblesenior@gmail.com> $
// ----------------------
// Modified for Zen Cart v1.5.0+ by lat9 (@vinosdefrutastropicales.com)
// ----------------------

require_once ('includes/application_top.php');

$ib_action = (isset($_GET) && isset($_GET['g'])) ? $_GET['g'] : '';
if (!ip_blocker_installed()) {
  if ($ib_action != 'install1' && !($ib_action == 'install' && isset($_POST) && isset($_POST['install_x']))) {
    zen_redirect(zen_href_link(FILENAME_IP_BLOCKER, 'g=install1'));
    
  }
} else {
  if ($ib_action == 'install') {
    zen_redirect(zen_href_link(FILENAME_IP_BLOCKER, 'g=uninstall1'));
    
  }
}

// menu
$ip_block_menu = array(
  'ip_settings' => IP_BLOCKER_MENU_IP_SETTINGS,
  'password_settings' => IP_BLOCKER_MENU_PASSWORD_SETTINGS,
  'install' => IP_BLOCKER_MENU_INSTALL,
  'enable' => IP_BLOCKER_MENU_POWER,
  'uninstall' => IP_BLOCKER_MENU_UNINSTALL
);

// action
$ip_block_action = trim($_GET['g']);
if ($ip_block_action == '') {
  $ip_block_action = 'ip_settings';
}

$message = '';
$message_status = true;
$message_show = false;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<style>
<!--
div, form, input, span, ul, li{ margin: 0; padding: 0; }
.d_title { margin: 10px 0 0 25px; }
.d_menu { margin-left: 5%;  font-size: 14px; }
.d_menu span { margin: 0 10px; }
.d_menu .d_current { font-weight: bold; }
.d_message { margin: 30px 10px; padding: 10px; text-align: center; font-weight: bold; width: 70%; }
.d_warning { border: 1px solid #FF0000; background-color:#FFF4F4; }
.d_menu_title { margin: 30px 0 0 10px; border-top: 1px solid #008A45; padding: 5px; background-color: #C0E0E0; width: 70%; font-weight: bold; }
.d_power{ margin: 35px 0 35px 10%; font-weight: bold; }
.d_w { width: 70%; }
.d_iplist { margin: 15px 0 15px 35px; }
.d_fb { font-weight: bold; }
ul.d_iplist { list-style: none; margin-top: 15px; margin-left: 8%; }
ul.d_iplist li { float: left; }
ul.d_iplist li.l { width: 20%; }
ul.d_iplist li.r { width: 80%; }
.d_c { clear: both; }
-->
</style>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript">
<!--
function init()
{
  cssjsmenu('navbar');
  if (document.getElementById)
  {
    var kill = document.getElementById('hoverJS');
    kill.disabled = true;
  }

}
//-->
</script>
</head>
<body onload="init();">
<!-- header //-->
<?php 
require(DIR_WS_INCLUDES . 'header.php'); 
?>
  <div class="pageHeading d_title"><?php echo IP_BLOCKER_TITLE; ?>

    <span class="d_menu">
<?php
foreach ($ip_block_menu as $menu_action => $menu) {
  if ($menu_action == 'install') {
    if (ip_blocker_installed()) {
      continue;
    }
  }
?>
      <span>-</span>
<?php
  if ($ip_block_action == $menu_action) {
?>
      <span class="_current">[ <?php echo $menu; ?> ]</span>
<?php
  } else { 
?>
      <a href="<?php echo zen_href_link(FILENAME_IP_BLOCKER, 'g=' . $menu_action); ?>" target="_self"><?php echo $menu; ?></a>
<?php
  }
}
?>
    </span>
  </div>

  <div class="d_menu_title">
<?php 
switch ($ip_block_action) {
  // IP settings
  case 'ip_settings': {   
    if (isset($_POST) && isset($_POST['blocklist'])) {
      $message_show = true;
      $blocklist = zen_db_prepare_input($_POST['blocklist']);
      $passlist = zen_db_prepare_input($_POST['passlist']);
      
      // ip blocklist
      ip_blocker_ip_list($blocklist);
      
      // ip passlist
      ip_blocker_ip_list($passlist, 'pass');
      
      $message_status = true;
      $message = IB_MESSAGE_IP_UPDATED_SUCCESS;
      
    }
    
    // Get ip list
    $ip_list = $db->Execute('SELECT ib_blocklist_string, ib_passlist_string FROM `' . TABLE_IP_BLOCKER . '` WHERE ib_id=1');
    
    $ip_blocklist = $ip_list->fields['ib_blocklist_string'];
    $ip_passlist = $ip_list->fields['ib_passlist_string'];
    echo IP_BLOCKER_MENU_IP_SETTINGS; 
?>
  </div>
  <div class="d_iplist d_w"><?php echo IP_BLOCKER_HELP_IP_SETTINGS; ?></div>
<?php 
    if ($message_show) {
?>
  <div class="d_message <?php echo $message_status ? 'd_ok' : 'd_warning'; ?>"><?php echo $message; ?></div>
<?php 
    }
?>
  <?php echo zen_draw_form('iplist', FILENAME_IP_BLOCKER, 'g=ip_settings'); ?>
    <ul class="d_iplist d_w">
      <li class="l d_fb"><?php echo IB_BLOCKED_RANGE; ?></li>
      <li class="r"><textarea name="blocklist" rows="15" cols="35"><?php echo $ip_blocklist; ?></textarea></li>
    </ul>
    <div class="d_c"></div>
    <ul class="d_iplist d_w">
      <li class="l d_fb"><?php echo IB_ALLOWED_RANGE; ?></li>
      <li class="r"><textarea name="passlist" rows="15" cols="35"><?php echo $ip_passlist; ?></textarea></li>
    </ul>
    <div class="d_c"></div>
    <div style="text-align: center; margin-top: 15px;"><?php echo zen_image_submit('button_update.gif', IMAGE_UPDATE, 'name="ip_settings"'); ?></div>
  </form>
<?php
    break;
  }  // End ip settings
 
  case 'password_settings': {   
    if (isset($_POST) && isset($_POST['pwd'])) {
      $message_show = true;
      
      $password = zen_db_prepare_input($_POST['pwd']);
      
      if ($password == '') {
        $message_status = false;
        $message = IB_MESSAGE_PASSWORD_REQUIRED_ERROR;
        
      } else {
        // Update password
        $db->Execute('UPDATE `' . TABLE_IP_BLOCKER . '` SET ib_password="' . ip_blocker_md5($password) . '" WHERE ib_id=1');
      
        $message_status = true;
        $message = IB_MESSAGE_PASSWORD_UPDATED_SUCCESS;
      }
    }
    $current_pwd = $db->Execute('SELECT ib_password FROM ' . TABLE_IP_BLOCKER . ' WHERE ib_id=1');
    $help_msg = ($current_pwd->fields['ib_password'] == ip_blocker_md5('123456')) ? IP_BLOCKER_HELP_PASSWORD_SETTINGS_DEFAULT : IB_PASSWORD_SET;
    echo IP_BLOCKER_MENU_PASSWORD_SETTINGS; 
?>
  </div>
  <div class="d_iplist d_w"><?php echo $help_msg; ?></div>
<?php
    if ($message_show) {
?>
  <div class="d_message <?php echo $message_status ? 'd_ok' : 'd_warning'; ?>"><?php echo $message; ?></div>
<?php 
    }
?>
  <div class="d_power d_w">
    <?php echo zen_draw_form('password', FILENAME_IP_BLOCKER, 'g=password_settings') . IB_TEXT_PASSWORD . ' ' . zen_draw_password_field('pwd'); ?>
      <div style="margin-top:35px"><?php echo zen_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
    </form>
  </div>
<?php
    break;
  }  // End password settings

  case 'enable': {    
    if (isset($_POST) && isset($_POST['enable']) && isset($_POST['lockout_count'])) {  //-v2.0.1c
      $message_show = true;
      
      $enable_ = (int)$_POST['enable'];  //-v2.0.1c
      $lockout_count = (int)$_POST['lockout_count'];  //-v2.0.1a
      
      // Update enable and lockout count settings
      $db->Execute("UPDATE " . TABLE_IP_BLOCKER . " SET ib_power = $enable_, ib_lockout_count = $lockout_count WHERE ib_id = 1");  //-v2.0.1c
      
      $message_status = true;
      $message = ((bool)$enable_) ? IB_FEATURE_ENABLED : IB_FEATURE_DISABLED;  //-v2.0.1c
    }
    
    // Get enable setting
    $enable = $db->Execute('SELECT ib_power, ib_lockout_count FROM `' . TABLE_IP_BLOCKER . '` WHERE ib_id=1');  //-v2.0.1c
    $enabled = (bool)$enable->fields['ib_power'];
    $lockout_count = $enable->fields['ib_lockout_count'];
    echo IP_BLOCKER_MENU_POWER; 
?>
  </div>
  <div class="d_iplist d_w"><?php echo IP_BLOCKER_HELP_POWER; ?></div>
<?php 
  if ($message_show) {
?>
  <div class="d_message <?php echo $message_status ? 'd_ok' : 'd_warning'; ?>"><?php echo $message; ?></div>
<?php 
  }
?>
  <div class="d_power d_w">
    <?php echo zen_draw_form('enable', FILENAME_IP_BLOCKER, 'g=enable') . zen_draw_radio_field('enable', '1', $enabled) . '&nbsp;&nbsp;<span style="color:blue">' . IB_ON . '</span>&nbsp;&nbsp;' . zen_draw_radio_field('enable', '0', !$enabled) . '&nbsp;&nbsp;<span style="color:red">' . IB_OFF . '</span><br /><br />' . IP_BLOCKER_LOCKOUT_COUNT . '&nbsp;&nbsp;' . zen_draw_input_field('lockout_count', $lockout_count, 'style="width: 2em;"'); //-v2.0.1c ?>
      <div style="margin-top:35px"><?php echo zen_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
    </form>
  </div>
<?php
    break;
  }  // End enable

  case 'install':
  case 'install1': {
    $install = false;
    
    if (isset($_POST) && isset($_POST['install_x'])) {
      $message_show = true;
      
      // Create table
      $db->Execute("
        CREATE TABLE `" . TABLE_IP_BLOCKER . "` (
          `ib_id` tinyint(1) unsigned NOT NULL auto_increment,
          `ib_blocklist` longtext NOT NULL,
          `ib_passlist` longtext NOT NULL,
          `ib_blocklist_string` longtext NOT NULL,
          `ib_passlist_string` longtext NOT NULL,
          `ib_password` varchar(50) NOT NULL,
          `ib_power` tinyint(1) DEFAULT 1,
          `ib_date` date NOT NULL,
          ib_lockout_count int(5) NOT NULL default 0,
          PRIMARY KEY  (`ib_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET
      );  //-v2.0.1c
      
      $db->Execute("
        INSERT INTO `" . TABLE_IP_BLOCKER . "` VALUES(
          NULL,
          '',
          '',
          '',
          '',
          '" . ip_blocker_md5('123456') . "',
          '1',
          '" . date('Y-m-d') . "',
          0
        )
      ");
      
      if ($db->error_number || $db->error_text) {
        $message_status = false;
        $message = 'DB ERROR : #' . $db->error_number . ' - ' . $this->error_text;
      } else {
        $message_status = true;
        $message = IB_INSTALL_OK;
        $install = true;
      }
    }
    echo IP_BLOCKER_MENU_INSTALL;
?>
  </div>
<?php
    if (!$install) {
?>
  <div class="d_message d_ok"><?php echo zen_draw_form('install', FILENAME_IP_BLOCKER, 'g=install') . IB_INSTALL_REQUIRED . '<br /><br />' . zen_image_submit('button_install.gif', IP_BLOCKER_MENU_INSTALL, 'name="install"') . '</form>'; ?></div>
<?php
    } elseif ($message_show) {
?>
  <div class="d_message <?php echo $message_status ? 'd_ok' : 'd_warning'; ?>"><?php echo $message; ?></div>
<?php 
    }
    break;
  }  // End Install

  case 'uninstall': {
    $uninstall = false;
    
    if (isset($_POST) && isset($_POST['uninstall_x'])) {
      // Uninstall start
      $uninstall = true;
      $message_show = true;
      
      // Drop table
      $db->Execute('DROP TABLE `' . TABLE_IP_BLOCKER . '`');
      
      $message_status = true;
      $message = IB_REMOVED;
    }
    echo IP_BLOCKER_MENU_UNINSTALL; 
?>
  </div>
<?php
    if (!$uninstall) { 
?>
  <div class="d_message d_warning">
    <?php echo zen_draw_form('uninstall', FILENAME_IP_BLOCKER, 'g=uninstall') . IB_UNINSTALL_VERIFY . '<br /><br />' . zen_image_submit('button_remove.gif', IP_BLOCKER_MENU_UNINSTALL, 'name="uninstall"'); ?></form>
  </div>
<?php
    } elseif ($message_show) {
?>
  <div class="d_message <?php echo $message_status ? 'd_ok' : 'd_warning'; ?>"><?php echo $message; ?></div>
<?php
    }
    break;
  }  // End uninstall
}
require(DIR_WS_INCLUDES . 'footer.php'); 
?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>