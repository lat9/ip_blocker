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

require_once('includes/application_top.php');

// -----
// Create the IP blocker password (previously in /YOUR_ADMIN/includes/functions/extra_functions/ip_blocker_functions.php.
//
function ip_blocker_md5($password)
{
    return md5(md5($password . '_secure_key'));
}

$message_pwd = '';
$message_blocklist = '';
$message_passlist = '';

if (isset ($_POST) && isset ($_GET['action']) && $_GET['action'] = 'process') {
    $pwd = ($_POST['pwd'] == $_POST['current_pwd']) ? $_POST['current_pwd'] : ip_blocker_md5($_POST['pwd']);
    $blocklist = zen_db_prepare_input($_POST['blocklist']);
    $message_blocklist = ip_blocker_save_iplist($blocklist, 'block');

    $passlist = zen_db_prepare_input($_POST['passlist']);
    $message_passlist = ip_blocker_save_iplist($passlist, 'pass');

    $enabled = (int)$_POST['enable'];
    $lockout_count = (int)$_POST['lockout_count'];

    if (($message_blocklist . $message_passlist) == '') {
        $db->Execute("UPDATE " . TABLE_IP_BLOCKER . " SET ib_power = $enabled, ib_lockout_count = $lockout_count, ib_password = '$pwd' WHERE ib_id = 1");
        $messageStack->add_session(IB_MESSAGE_UPDATED, 'success');
        zen_redirect(zen_href_link(FILENAME_IP_BLOCKER));
    }

} else {
    $ip_list = $db->Execute('SELECT * FROM ' . TABLE_IP_BLOCKER . ' WHERE ib_id=1');
    $enabled = (int)$ip_list->fields['ib_power'];
    $lockout_count = $ip_list->fields['ib_lockout_count'];
    $pwd = $ip_list->fields['ib_password'];
    $blocklist = ip_blocker_array_to_list(unserialize($ip_list->fields['ib_blocklist']));
    $blocklist = (is_array($blocklist)) ? implode("\r\n", $blocklist) : '';
    $passlist = ip_blocker_array_to_list(unserialize($ip_list->fields['ib_passlist']));
    $passlist = (is_array($passlist)) ? implode("\r\n", $passlist) : '';
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
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="container-fluid">
    <h1><?php echo HEADING_TITLE; ?></h1>
    <!-- body_text //-->
    <div class="row"><?php echo zen_draw_form('blocker', FILENAME_IP_BLOCKER, 'action=process'); ?>
        <div><?php echo IB_TEXT_INSTRUCTIONS; ?></div>

        <div class="form-group">
            <div class="control-label" style="font-weight: 700;"><?php echo IB_TEXT_ENABLE; ?></div>
            <div class="radio">
                <label><?php echo zen_draw_radio_field('enable', '1', $enabled) . TEXT_YES; ?></label>
            </div>
            <div class="radio">
                <label><?php echo zen_draw_radio_field('enable', '0', !$enabled) . TEXT_NO; ?></label>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_SPECIAL_LOGIN_COUNT, 'lockout_count', 'class="control-label"'); ?>
            <div class="input"><?php echo zen_draw_input_field('lockout_count', $lockout_count, 'id="lockout_count" size="3"'); ?></div>
            <?php echo IB_TEXT_SPECIAL_LOGIN_COUNT_INFO; ?>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(IB_TEXT_SPECIAL_LOGIN_PASSWORD, 'pwd', 'class="control-label"'); ?>
            <div class="input"><?php echo zen_draw_password_field('pwd', $pwd, true, 'id="pwd"') . zen_draw_hidden_field('current_pwd', $pwd); ?></div>
        </div>

        <div class="row" style="margin-top:15px">
            <h2><?php echo IB_TEXT_IP_ADDRESS; ?></h2>
            <?php echo IB_TEXT_IP_ADDRESS_INFO; ?>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <?php echo zen_draw_label(IB_TEXT_BLOCKED_RANGE, 'blocklist', 'class="control-label"');
                if ($message_blocklist != '') { ?>
                    <div class="errorText"><?php echo $message_blocklist; ?></div>
                <?php } ?>
                <?php echo zen_draw_textarea_field('blocklist', 'soft', 10, 15, $blocklist, 'id="blocklist"'); ?></div>

            <div class="col-sm-2 col-md-2 col-lg-2">
                <?php echo zen_draw_label(IB_TEXT_ALLOWED_RANGE, 'passlist', 'class="control-label"');
                if ($message_passlist != '') { ?>
                    <div class="errorText"><?php echo $message_passlist; ?></div>
                <?php } ?>
                <?php echo zen_draw_textarea_field('passlist', 'soft', 10, 15, $passlist, 'id="passlist"'); ?>
            </div>
        </div>
        <div style="margin-top:15px"><?php echo zen_image_submit('button_update.gif', IMAGE_UPDATE); ?></div>
        <?php echo '</form>'; ?>
    </div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
