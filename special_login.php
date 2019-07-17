<?php //Plugin: IP blocker
require_once('includes/application_top.php');

if (isset($_SESSION['ip_blocker_pass']) && $_SESSION['ip_blocker_pass'] == true) {
    zen_redirect(zen_href_link(FILENAME_DEFAULT));  //-v2.0.2c
}

if (isset($_SESSION['ip_blocker_fail'])) {
    exit();
}  //-v2.0.1a

$message_show = false;

if (isset($_POST) && isset($_POST['pwd'])) {
    $message_show = true;

    $password = zen_db_prepare_input($_POST['pwd']);

    if ($password == '') {
        $message = 'Password required !';
    } else {
        $password = md5(md5($password . '_secure_key'));

        $lockout_field = ($sniffer->field_exists(TABLE_IP_BLOCKER, 'ib_lockout_count')) ? ', ib_lockout_count' : '';  //-v2.0.1a
        $password_fields = $db->Execute("SELECT ib_password$lockout_field FROM " . TABLE_IP_BLOCKER . ' WHERE ib_id=1'); //-v2.0.1c
        $ib_password = $password_fields->fields['ib_password'];  //-v2.0.1c

        if ($ib_password != '' && $password != $ib_password) {
            $message = 'Wrong password!';
//-bof-v2.0.1a
            if ($lockout_field != '' && $password_fields->fields['ib_lockout_count'] != 0) {
                if (!isset($_SESSION['ib_lockout_count'])) {
                    $_SESSION['ib_lockout_count'] = 1;

                } else {
                    $_SESSION['ib_lockout_count']++;

                }
                if ($_SESSION['ib_lockout_count'] >= $password_fields->fields['ib_lockout_count']) {
                    $_SESSION['ip_blocker_fail'] = 1;
                    exit();
                }
            }
//-eof-v2.0.1a

        } elseif ($ib_password != '' && $password == $ib_password) {
            $_SESSION['ip_blocker_pass'] = true;
            unset ($_SESSION['ib_lockout_count']);  //-v2.0.2a
            zen_redirect(zen_href_link(FILENAME_DEFAULT));  //-v2.0.2c-Use built in zen functions

        }
    }
}
?>
    <!doctype html>
    <html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <style>
            div, input, form, span {
                margin: 0;
                padding: 0;
            }
            body {
                font-size: 14px;
                font-family: Verdana, Arial, Helvetica, sans-serif;
            }
            .d_w {
                width: 45%;
                margin: 10% auto;
                border: 3px solid #0000FF;
                padding: 5px;
            }
            .d_message {
                width: 70%;
                margin: 0 auto 35px auto;
                padding: 5px;
                border: 1px solid #ff0000;
                background-color: #FFECEC;
                font-weight: bold;
            }
            .d_t {
                font-size: 18px;
                padding: 10px;
                border-bottom: 1px solid #8C8CFF;
            }

            .d_p {
                text-align: center;
                margin: 35px 0;
            }
        </style>
    </head>

    <body>
    <div class="d_w">
        <div class="d_t">Your IP address has been blocked from this site ....</div>
        <form action="#" name="i_l" method="POST" target="_self">
            <div class="d_p">
                <label for="pwd">Password</label>
                <input type="password" name="pwd" id="pwd" size="35" value=""/>
                <input type="submit" value="Login"/></div>
            <?php
            if ($message_show) {
                ?>
                <div class="d_message"><?php echo $message; ?></div>
                <?php
            }
            ?>
        </form>
        <script>document.i_l.pwd.focus()</script>
    </div>
    </body>
    </html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>