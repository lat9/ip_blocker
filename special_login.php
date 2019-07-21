<?php
// -----
// Part of the "IP Blocker" plugin, provided by lat9 and torvista.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales.
//
require 'includes/application_top.php';

if (!empty($_SESSION['ip_blocker_pass'])) {
    zen_redirect(zen_href_link(FILENAME_DEFAULT));
}

if (isset($_SESSION['ip_blocker_fail'])) {
    exit();
}

$message_show = false;

if (isset($_POST['pwd'])) {
    $message_show = true;

    $password = (string)$_POST['pwd'];

    if ($password == '') {
        $message = 'Password required !';
    } else {
        $password = md5(md5($password . '_secure_key'));

        $password_fields = $db->Execute("SELECT * FROM " . TABLE_IP_BLOCKER . ' WHERE ib_id=1');
        $ib_password = $password_fields->fields['ib_password'];

        if ($ib_password != '' && $password != $ib_password) {
            $message = 'Wrong password!';

            if ($password_fields->fields['ib_lockout_count'] != 0) {
                if (!isset($_SESSION['ib_lockout_count'])) {
                    $_SESSION['ib_lockout_count'] = 0;
                }
                $_SESSION['ib_lockout_count']++;

                if ($_SESSION['ib_lockout_count'] >= $password_fields->fields['ib_lockout_count']) {
                    $_SESSION['ip_blocker_fail'] = 1;
                    exit();
                }
            }

        } elseif ($ib_password != '' && $password == $ib_password) {
            $_SESSION['ip_blocker_pass'] = true;
            unset($_SESSION['ib_lockout_count']);
            zen_redirect(zen_href_link(FILENAME_DEFAULT));
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
        <div class="d_t"><?php echo TEXT_SPECIAL_LOGIN_INSTRUCTIONS; ?></div>
        <form action="#" name="i_l" method="POST" target="_self">
            <div class="d_p">
                <label for="pwd">Password</label>
                <input type="password" name="pwd" id="pwd" size="35" value="" />
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
<?php 
require DIR_WS_INCLUDES . 'application_bottom.php';
