<?php
// -----
// Part of the "IP Blocker" plugin, provided by lat9 and torvista for continued use under Zen Cart 1.5.6 and later.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales.
//
// $Id: ip_blocker.php, v1.0.0 2009/09/09 $d <noblesenior@gmail.com> $
// ----------------------
// Modified for Zen Cart v1.5.5+ by lat9 (@vinosdefrutastropicales.com)
// ----------------------

require_once 'includes/application_top.php';

// -----
// Create the IP blocker password (previously in /YOUR_ADMIN/includes/functions/extra_functions/ip_blocker_functions.php.
//
function ip_blocker_md5($password)
{
    if ($password === '') {
        return '';
    } else {
        return md5(md5($password . '_secure_key'));
    }
}

$message_pwd = '';
$message_blocklist = '';
$message_passlist = '';

$ip_list = $db->Execute('SELECT * FROM ' . TABLE_IP_BLOCKER . ' WHERE ib_id=1');

if (isset($_GET['action']) && $_GET['action'] == 'process') {
    $pwd = ($_POST['pwd'] == $_POST['current_pwd']) ? $_POST['current_pwd'] : ip_blocker_md5($_POST['pwd']);
    $blocklist = zen_db_prepare_input($_POST['blocklist']);
    $message_blocklist = ip_blocker_save_iplist($blocklist, 'block');

    $passlist = zen_db_prepare_input($_POST['passlist']);
    $message_passlist = ip_blocker_save_iplist($passlist, 'pass');

    $enabled = (int)$_POST['enable'];
    $lockout_count = (int)$_POST['lockout_count'];
    $block_invalid_ip = (int)$_POST['block_invalid_ip'];

    if (($message_blocklist . $message_passlist) == '') {
        $db->Execute("UPDATE " . TABLE_IP_BLOCKER . " SET ib_power = $enabled, ib_lockout_count = $lockout_count, ib_password = '$pwd', ib_block_invalid_ip = $block_invalid_ip WHERE ib_id = 1");
        $messageStack->add_session(IB_MESSAGE_UPDATED, 'success');
        zen_redirect(zen_href_link(FILENAME_IP_BLOCKER));
    }

} else {
    $enabled = $ip_list->fields['ib_power'];
    $lockout_count = $ip_list->fields['ib_lockout_count'];
    $pwd = $ip_list->fields['ib_password'];
    $block_invalid_ip = $ip_list->fields['ib_block_invalid_ip'];
    
    if (empty($ip_list->fields['ib_blocklist'])) {
        $blocklist = '';
    } else {
        $blocklist = ip_blocker_array_to_list(unserialize($ip_list->fields['ib_blocklist']));
        $blocklist = (is_array($blocklist)) ? implode("\r\n", $blocklist) : '';
    }
    
    if (empty($ip_list->fields['ib_passlist'])) {
        $passlist = '';
    } else {
        $passlist = ip_blocker_array_to_list(unserialize($ip_list->fields['ib_passlist']));
        $passlist = (is_array($passlist)) ? implode("\r\n", $passlist) : '';
    }
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta charset="<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" media="print" href="includes/stylesheet_print.css">
    <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
    <script src="includes/menu.js"></script>
    <script src="includes/general.js"></script>
    <script>
        function init() {
            cssjsmenu('navbar');
            if (document.getElementById) {
                var kill = document.getElementById('hoverJS');
                kill.disabled = true;
            }
        }
    </script>
</head>
<body onload="init();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div class="container-fluid">
    <h1><?php echo sprintf(HEADING_TITLE, $ip_list->fields['ib_version']); ?></h1>
    <!-- body_text //-->
    <div class="row"><?php echo IB_TEXT_INSTRUCTIONS; ?></div>
    <div class="row"><?php echo zen_draw_form('blocker', FILENAME_IP_BLOCKER, 'action=process', 'post', 'class="form-horizontal"'); ?>
        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_ENABLE, 'enable', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                 <label class="radio-inline"><?php echo zen_draw_radio_field('enable', '1', $enabled) . TEXT_YES; ?></label>
                 <label class="radio-inline"><?php echo zen_draw_radio_field('enable', '0', !$enabled) . TEXT_NO; ?></label>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_SPECIAL_LOGIN_COUNT, 'lockout_count', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <?php echo zen_draw_input_field('lockout_count', $lockout_count, 'id="lockout_count" class="form-control"'); ?>
                <br /><span class="help-block"><?php echo IB_TEXT_SPECIAL_LOGIN_COUNT_INFO; ?></span>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_SPECIAL_LOGIN_PASSWORD, 'pwd', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <?php echo zen_draw_input_field('pwd', $pwd, 'id="pwd" class="form-control"') . zen_draw_hidden_field('current_pwd', $pwd); ?>
                <br /><span class="help-block"><?php echo IB_TEXT_PASSWORD_INFO; ?></span>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(IB_AUTO_BLOCK_INVALID_IPS, 'block_invalid_ip', 'class="col-sm-3 control-label"'); ?>
           <div class="col-sm-9 col-md-6">
                 <label class="radio-inline"><?php echo zen_draw_radio_field('block_invalid_ip', '1', $block_invalid_ip) . TEXT_YES; ?></label>
                 <label class="radio-inline"><?php echo zen_draw_radio_field('block_invalid_ip', '0', !$block_invalid_ip) . TEXT_NO; ?></label>
                 <br /><br /><span class="help-block"><?php echo IB_AUTO_BLOCK_INFO; ?></span>
            </div>
        </div>

<?php
$info_text = '<p class="help-block">' . IB_TEXT_IP_ADDRESS_INFO . '</p>';
if ($message_blocklist != '') {
    $message_blocklist = '<p class="help-block errorText">' . $message_blocklist . '</p>';
}
?>
        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_BLOCKED_RANGE, 'blocklist', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <?php echo $info_text . $message_blocklist . zen_draw_textarea_field('blocklist', 'soft', 10, 5, htmlspecialchars($blocklist, ENT_COMPAT, CHARSET, TRUE), 'class="noEditor form-control"'); ?>
            </div>
        </div>
<?php
if ($message_passlist != '') {
    $message_passlist = '<p class="help-block errorText">' . $message_passlist . '</p>';
}
?>
        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_ALLOWED_RANGE, 'passlist', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <?php echo $info_text . $message_passlist . zen_draw_textarea_field('passlist', 'soft', 10, 5, htmlspecialchars($passlist, ENT_COMPAT, CHARSET, TRUE), 'class="noEditor form-control"'); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-primary"><?php echo IMAGE_UPDATE; ?></button>
            </div>
        </div>
        <?php echo '</form>'; ?>
    </div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php 
require DIR_WS_INCLUDES . 'application_bottom.php';
